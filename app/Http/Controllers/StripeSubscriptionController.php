<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Services\PricingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Exception;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeSubscriptionController extends Controller
{
    protected $stripeService;
    protected $pricingService;

    public function __construct(StripeService $stripeService, PricingService $pricingService)
    {
        $this->stripeService = $stripeService;
        $this->pricingService = $pricingService;
    }

    /**
     * Create Stripe subscription checkout session
     */
    public function createSubscription()
    {
        try {
            Log::info('Creating Stripe subscription for trial user', [
                'user_id' => Auth::id(),
                'user_email' => Auth::user()?->email,
                'session_data' => session()->all()
            ]);

            // Pre-flight checks
            $checks = $this->performPreFlightChecks();
            if (!$checks['passed']) {
                Log::warning('Pre-flight checks failed', $checks);
                return redirect()->route('trial.payment.options')->with('error', $checks['error']);
            }

            // Get validated data from session
            $validatedData = session('validatedData');
            if (!$validatedData) {
                Log::warning('No validated data in session for user', ['user_id' => Auth::id()]);
                return redirect()->route('try-writing')->with('error', 'No data found. Please try again.');
            }

            // Get selected plan from session (from welcome page) or default to starter
            $selectedPlan = session('selected_plan', 'starter');

            // Validate selected plan
            $validPlans = ['starter', 'professional', 'enterprise'];
            if (!in_array($selectedPlan, $validPlans)) {
                Log::warning('Invalid plan selected, defaulting to starter', [
                    'plan' => $selectedPlan,
                    'valid_plans' => $validPlans,
                    'user_id' => Auth::id()
                ]);
                $selectedPlan = 'starter'; // Default to starter
                session(['selected_plan' => $selectedPlan]); // Update session
            }

            // Get pricing for the selected plan
            $planPrice = $this->pricingService->getPlanPrice($selectedPlan);
            if (!$planPrice || $planPrice <= 0) {
                Log::error('Invalid plan price retrieved', [
                    'plan' => $selectedPlan,
                    'price' => $planPrice,
                    'user_id' => Auth::id()
                ]);
                return redirect()->route('trial.payment.options')->with('error', 'Pricing error. Please try again.');
            }
            $priceInCents = (int)($planPrice * 100); // Convert to cents for Stripe

            Log::info('Using dynamic pricing for subscription', [
                'selected_plan' => $selectedPlan,
                'plan_price' => $planPrice,
                'price_in_cents' => $priceInCents,
                'user_id' => Auth::id(),
            ]);

            // Check Stripe configuration
            $stripeSecretKey = config('services.stripe.secret_key');
            if (!$stripeSecretKey) {
                Log::error('Stripe secret key not configured');
                return redirect()->route('trial.payment.options')->with('error', 'Payment service not configured. Please contact support.');
            }

            // Set Stripe API key
            Stripe::setApiKey($stripeSecretKey);

            // Get plan details for product description
            $formattedPricing = $this->pricingService->getFormattedPricing();
            $planDetails = $formattedPricing[$selectedPlan] ?? $formattedPricing['starter'];

            // Validate routes exist
            $successUrl = route('stripe.subscription.success');
            $cancelUrl = route('stripe.subscription.cancel');

            // Create subscription checkout session
            $checkoutSessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => ucfirst($selectedPlan) . ' Plan - Medical Content Writing',
                            'description' => $planDetails['articles'] . ' premium articles per month by licensed M.D. & M.B.B.S. writers',
                        ],
                        'unit_amount' => $priceInCents,
                        'recurring' => [
                            'interval' => 'month',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'customer_id' => Auth::id(),
                    'customer_email' => Auth::user()->email,
                    'trial_data' => json_encode($validatedData),
                    'subscription_type' => $selectedPlan . '_monthly',
                    'selected_plan' => $selectedPlan,
                    'plan_price' => $planPrice,
                    'articles_per_month' => $planDetails['articles'],
                ],
                'customer_email' => Auth::user()->email,
            ];

            Log::info('Creating Stripe checkout session with data', [
                'user_id' => Auth::id(),
                'plan' => $selectedPlan,
                'amount' => $priceInCents,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ]);

            $checkoutSession = \Stripe\Checkout\Session::create($checkoutSessionData);

            Log::info('Stripe subscription checkout created', [
                'session_id' => $checkoutSession->id,
                'user_id' => Auth::id(),
            ]);

            // Store session data
            session(['stripe_subscription_session_id' => $checkoutSession->id]);

            return redirect()->away($checkoutSession->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error during subscription creation', [
                'message' => $e->getMessage(),
                'stripe_code' => $e->getStripeCode(),
                'http_status' => $e->getHttpStatus(),
                'user_id' => Auth::id(),
                'plan' => $selectedPlan ?? 'unknown'
            ]);

            // Provide user-friendly error messages based on Stripe error codes
            $stripeCode = $e->getStripeCode();
            $errorMessage = 'Payment processing failed. Please try again or contact support.';

            if ($stripeCode === 'card_declined') {
                $errorMessage = 'Your card was declined. Please try a different payment method.';
            } elseif ($stripeCode === 'insufficient_funds') {
                $errorMessage = 'Insufficient funds on your card. Please try a different payment method.';
            } elseif ($stripeCode === 'generic_decline') {
                $errorMessage = 'Your card was declined. Please contact your bank or try a different card.';
            } elseif ($stripeCode === 'invalid_card_type') {
                $errorMessage = 'This card type is not accepted. Please use a Visa, Mastercard, or American Express.';
            } elseif ($stripeCode === 'expired_card') {
                $errorMessage = 'Your card has expired. Please use a different card.';
            }

            return redirect()->route('trial.payment.options')->with('error', $errorMessage);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe invalid request error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'plan' => $selectedPlan ?? 'unknown'
            ]);
            return redirect()->route('trial.payment.options')->with('error', 'Invalid payment request. Please contact support.');

        } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error('Stripe authentication error - check API keys', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('trial.payment.options')->with('error', 'Payment service temporarily unavailable. Please try again later.');

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            Log::error('Stripe connection error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('trial.payment.options')->with('error', 'Connection error. Please check your internet and try again.');

        } catch (Exception $e) {
            Log::error('General error during subscription creation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'plan' => $selectedPlan ?? 'unknown'
            ]);
            return redirect()->route('trial.payment.options')->with('error', 'An unexpected error occurred. Please try again or contact support.');
        }
    }

    /**
     * Handle successful subscription
     */
    public function subscriptionSuccess(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            Log::info('Stripe subscription success callback', ['session_id' => $sessionId]);

            if (!$sessionId) {
                return redirect()->route('trial.payment.options')->with('error', 'Session ID not found.');
            }

            // Retrieve session
            Stripe::setApiKey(config('services.stripe.secret_key'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Get plan details from metadata
                $selectedPlan = $session->metadata['selected_plan'] ?? 'starter';
                $planPrice = $session->metadata['plan_price'] ?? $this->pricingService->getStarterPrice();
                $articlesPerMonth = $session->metadata['articles_per_month'] ?? 5;

                // Create subscription record
                $subscription = Subscription::create([
                    'user_id' => Auth::id(),
                    'stripe_subscription_id' => $session->subscription,
                    'stripe_customer_id' => $session->customer,
                    'status' => 'active',
                    'package_type' => $selectedPlan . '_monthly',
                    'amount_paid' => $planPrice,
                    'credits_added' => 0, // No credits for subscription
                    'billing_cycle' => 'monthly',
                    'current_period_start' => now(),
                    'current_period_end' => now()->addMonth(),
                ]);

                // Create transaction record
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'subscription_id' => $subscription->id,
                    'amount' => 1.00,
                    'type' => 'subscription',
                    'status' => 'completed',
                    'payment_method' => 'stripe',
                    'payment_transaction_id' => $sessionId,
                ]);

                Log::info('Subscription created successfully', [
                    'subscription_id' => $subscription->id,
                    'stripe_subscription_id' => $session->subscription,
                    'user_id' => Auth::id(),
                ]);

                // Clear session
                session()->forget(['stripe_subscription_session_id', 'validatedData']);

                return redirect()->route('trial.success')->with('success', 'Subscription activated successfully!');
            }

            return redirect()->route('trial.payment.options')->with('error', 'Payment was not completed.');

        } catch (Exception $e) {
            Log::error('Subscription success processing failed', [
                'message' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);

            return redirect()->route('trial.payment.options')->with('error', 'Subscription processing failed.');
        }
    }

    /**
     * Handle subscription cancellation
     */
    public function subscriptionCancel()
    {
        Log::info('Stripe subscription cancelled by user', ['user_id' => Auth::id()]);

        // Clear session
        session()->forget('stripe_subscription_session_id');

        return redirect()->route('trial.payment.options')->with('error', 'Subscription was cancelled.');
    }

    /**
     * Perform pre-flight checks before creating subscription
     */
    private function performPreFlightChecks(): array
    {
        // Check authentication
        if (!auth()->check()) {
            return [
                'passed' => false,
                'error' => 'Please login first.',
                'check' => 'authentication'
            ];
        }

        // Check Stripe configuration
        if (!config('services.stripe.secret_key')) {
            Log::error('Stripe secret key not configured');
            return [
                'passed' => false,
                'error' => 'Payment service not configured. Please contact support.',
                'check' => 'stripe_config'
            ];
        }

        // Check user email
        if (!auth()->user()->email) {
            return [
                'passed' => false,
                'error' => 'User email is required for subscription. Please update your profile.',
                'check' => 'user_email'
            ];
        }

        // Check if pricing service is working
        try {
            $testPrice = $this->pricingService->getStarterPrice();
            if (!$testPrice || $testPrice <= 0) {
                Log::error('Pricing service returned invalid price', ['price' => $testPrice]);
                return [
                    'passed' => false,
                    'error' => 'Pricing configuration error. Please contact support.',
                    'check' => 'pricing_service'
                ];
            }
        } catch (Exception $e) {
            Log::error('Pricing service error', ['error' => $e->getMessage()]);
            return [
                'passed' => false,
                'error' => 'Pricing service unavailable. Please try again later.',
                'check' => 'pricing_service'
            ];
        }

        return ['passed' => true];
    }

    /**
     * Handle Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');
            $webhookSecret = config('services.stripe.webhook_secret');

            if (!$webhookSecret) {
                Log::error('Stripe webhook secret not configured');
                return response()->json(['error' => 'Webhook secret not configured'], 500);
            }

            // Verify webhook signature
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);

            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'event_id' => $event->id,
            ]);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($event->data->object);
                    break;

                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', [
                'message' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Processing checkout.session.completed', [
            'session_id' => $session->id,
            'subscription_id' => $session->subscription,
        ]);

        // This is handled in the success callback, but webhook provides additional confirmation
    }

    private function handleSubscriptionCreated($subscription)
    {
        Log::info('Processing customer.subscription.created', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
            'status' => $subscription->status,
        ]);

        // Update subscription status if needed
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end),
            ]);
        }
    }

    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Processing customer.subscription.updated', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
        ]);

        // Update subscription details
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end),
            ]);
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Processing customer.subscription.deleted', [
            'subscription_id' => $subscription->id,
        ]);

        // Cancel subscription
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription->id)->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
        }
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Processing invoice.payment_succeeded', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $invoice->subscription,
            'amount_paid' => $invoice->amount_paid / 100, // Convert from cents
        ]);

        // Record successful payment
        $localSubscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        if ($localSubscription) {
            Transaction::create([
                'user_id' => $localSubscription->user_id,
                'subscription_id' => $localSubscription->id,
                'amount' => $invoice->amount_paid / 100,
                'type' => 'subscription_renewal',
                'status' => 'completed',
                'payment_method' => 'stripe',
                'payment_transaction_id' => $invoice->id,
            ]);
        }
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        Log::info('Processing invoice.payment_failed', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $invoice->subscription,
        ]);

        // Handle failed payment - could suspend subscription or notify user
        $localSubscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();
        if ($localSubscription) {
            // Could add logic to handle failed payments
            Log::warning('Subscription payment failed', [
                'subscription_id' => $localSubscription->id,
                'user_id' => $localSubscription->user_id,
            ]);
        }
    }
}

