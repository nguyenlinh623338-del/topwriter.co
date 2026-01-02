<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Transaction;
use Exception;

class StripeCreditsController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Checkout $600 for 30 credits with Stripe (Using successful payment formula)
     */
    public function checkout()
    {
        try {
            Log::info('Starting Stripe credits checkout', ['user_id' => Auth::id()]);

            if (!auth()->check()) {
                return redirect()->route('credits.checkout')->with('error', 'Please login before payment.');
            }

            // Amount for 30 credits: $600
            $amount = 600.00;

            // Create Stripe checkout session
            $session = $this->stripeService->createCreditsPayment(
                $amount,
                '30 Credits Package',
                [
                    'credits_amount' => '30',
                    'package_type' => 'credits',
                ]
            );

            Log::info('Stripe credits session created', ['session_id' => $session->id]);

            // Create transaction record with fallback for missing columns
            try {
                $transaction = new Transaction();
                $transaction->user_id = Auth::id();
                $transaction->amount = $amount;
                $transaction->type = 'credits';
                $transaction->status = 'pending';

                // Only set these fields if columns exist (production safety)
                if (Schema::hasColumn('transactions', 'payment_method')) {
                    $transaction->payment_method = 'stripe';
                }
                if (Schema::hasColumn('transactions', 'transaction_type')) {
                    $transaction->transaction_type = 'credits';
                }
                if (Schema::hasColumn('transactions', 'payment_status')) {
                    $transaction->payment_status = 'pending';
                }
                if (Schema::hasColumn('transactions', 'credits')) {
                    $transaction->credits = 30;
                }
                if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                    $transaction->payment_transaction_id = $session->id;
                }

                $transaction->save();

            } catch (Exception $e) {
                Log::error('Failed to create Stripe transaction record', [
                    'error' => $e->getMessage(),
                    'session_id' => $session->id
                ]);

                // Fallback: Create minimal transaction record
                $transaction = new Transaction([
                    'user_id' => Auth::id(),
                    'amount' => $amount,
                    'type' => 'credits',
                    'status' => 'pending'
                ]);
                $transaction->save();
            }

            // Save transaction ID to session
            session(['stripe_credits_transaction_id' => $transaction->id]);
            session(['stripe_session_id' => $session->id]);

            Log::info('Saved Stripe credits transaction to session', [
                'transaction_id' => $transaction->id,
                'session_id' => $session->id
            ]);

            // Redirect to Stripe checkout
            Log::info('Redirecting to Stripe checkout page', ['url' => $session->url]);
            return redirect()->away($session->url);

        } catch (Exception $e) {
            Log::error('Stripe credits checkout failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'Failed to create Stripe payment. Please try again.');
        }
    }

    /**
     * Handle successful credits payment (Using successful payment formula)
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            Log::info('Stripe credits success callback received', ['session_id' => $sessionId]);

            if (!$sessionId) {
                return redirect()->route('credits.checkout')->with('error', 'Payment session not found.');
            }

            // Retrieve session from Stripe to verify payment
            $session = $this->stripeService->getSession($sessionId);

            if ($session->payment_status !== 'paid') {
                Log::warning('Stripe payment not completed', [
                    'session_id' => $sessionId,
                    'payment_status' => $session->payment_status
                ]);
                return redirect()->route('credits.checkout')->with('error', 'Payment was not completed.');
            }

            // Get transaction from session
            $transactionId = session('stripe_credits_transaction_id');
            if ($transactionId) {
                $transaction = Transaction::find($transactionId);

                if ($transaction) {
                    // Update transaction status with column checks
                    $updateData = ['status' => 'completed'];

                    if (Schema::hasColumn('transactions', 'payment_status')) {
                        $updateData['payment_status'] = 'completed';
                    }
                    if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                        $updateData['payment_transaction_id'] = $sessionId;
                    }
                    if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                        $updateData['payment_completed_at'] = now();
                    }

                    $transaction->update($updateData);

                    Log::info('Stripe credits transaction updated', [
                        'transaction_id' => $transactionId,
                        'update_data' => $updateData
                    ]);

                    // Add credits to user (NO REFUND for credits)
                    $user = User::find($transaction->user_id);
                    if ($user) {
                        $oldCredits = $user->credits;
                        $user->credits += 30;
                        $user->save();

                        Log::info('Credits added to user account via Stripe', [
                            'user_id' => $user->id,
                            'previous_credits' => $oldCredits,
                            'added_credits' => 30,
                            'new_total_credits' => $user->credits
                        ]);
                    }
                }
            }

            // Clear session
            session()->forget('stripe_credits_transaction_id');
            session()->forget('stripe_session_id');

            return redirect()->route('credits.success')->with('success', '30 credits have been added to your account via Stripe!');

        } catch (Exception $e) {
            Log::error('Stripe credits success processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        try {
            Log::info('Stripe credits payment cancelled by user');

            // Get transaction ID from session
            $transactionId = session('stripe_credits_transaction_id');

            if ($transactionId) {
                // Get transaction from database
                $transaction = Transaction::find($transactionId);

                if ($transaction) {
                    // Update transaction status
                    $transaction->status = 'cancelled';
                    if (Schema::hasColumn('transactions', 'payment_status')) {
                        $transaction->payment_status = 'cancelled';
                    }
                    $transaction->save();

                    Log::info('Stripe credits transaction marked as cancelled', ['transaction_id' => $transactionId]);
                }

                // Clear session data
                session()->forget('stripe_credits_transaction_id');
                session()->forget('stripe_session_id');
            }

            return redirect()->route('credits.checkout')->with('error', 'Payment was cancelled.');
        } catch (\Exception $e) {
            Log::error('Error handling Stripe credits cancellation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'An error occurred while processing the cancellation.');
        }
    }
}
