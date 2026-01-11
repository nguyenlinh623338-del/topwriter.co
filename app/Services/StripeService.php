<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    private $secretKey;
    private $publicKey;
    private $webhookSecret;
    private $minimalAmount = 0.50; // Stripe minimum amount

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret_key');
        $this->publicKey = config('services.stripe.public_key');
        $this->webhookSecret = config('services.stripe.webhook_secret');
        
        // Set Stripe API key
        if (!empty($this->secretKey)) {
            Stripe::setApiKey($this->secretKey);
        }
        
        Log::info('Stripe Service initialized', [
            'public_key_set' => !empty($this->publicKey),
            'secret_key_set' => !empty($this->secretKey),
            'webhook_secret_set' => !empty($this->webhookSecret),
        ]);
    }

    /**
     * Create a Stripe Checkout Session for credits payment
     *
     * @param float $amount Amount in USD
     * @param string $description Description of the payment
     * @param array $metadata Additional metadata
     * @return Session Stripe Checkout Session
     */
    public function createCreditsPayment($amount, $description = '30 Credits Package', $metadata = [])
    {
        try {
            // Validate amount
            if ($amount < $this->minimalAmount) {
                $amount = $this->minimalAmount;
            }

            // Ensure amount is in cents for Stripe
            $amountInCents = (int)($amount * 100);

            // Default metadata
            $defaultMetadata = [
                'customer_id' => auth()->id(),
                'customer_email' => auth()->user()->email ?? '',
                'package_type' => 'credits',
                'credits_amount' => '30',
            ];

            // Merge with provided metadata
            $finalMetadata = array_merge($defaultMetadata, $metadata);

            Log::info('Creating Stripe Checkout Session', [
                'amount' => $amount,
                'amount_in_cents' => $amountInCents,
                'user_id' => auth()->id(),
            ]);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $description,
                            'description' => 'Premium credits package - 30 credits for high-quality content writing',
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => config('services.stripe.credits_return_url') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => config('services.stripe.credits_cancel_url'),
                'metadata' => $finalMetadata,
                'customer_email' => auth()->user()->email ?? null,
            ]);

            Log::info('Stripe Checkout Session created', [
                'session_id' => $session->id,
                'url' => $session->url,
            ]);

            return $session;

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error', [
                'message' => $e->getMessage(),
                'type' => $e->getStripeCode(),
            ]);
            throw new Exception('Stripe payment creation failed: ' . $e->getMessage());
        } catch (Exception $e) {
            Log::error('Stripe Payment Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Stripe payment creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a Stripe Checkout Session
     *
     * @param string $sessionId Session ID
     * @return Session Stripe Checkout Session
     */
    public function getSession($sessionId)
    {
        try {
            $session = Session::retrieve($sessionId);
            return $session;
        } catch (ApiErrorException $e) {
            Log::error('Stripe Get Session Error', [
                'session_id' => $sessionId,
                'message' => $e->getMessage(),
            ]);
            throw new Exception('Failed to retrieve Stripe session: ' . $e->getMessage());
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload Raw webhook payload
     * @param string $signature Stripe signature header
     * @return \Stripe\Event Stripe event object
     */
    public function verifyWebhook($payload, $signature)
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $this->webhookSecret
            );
            return $event;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Signature Verification Failed', [
                'message' => $e->getMessage(),
            ]);
            throw new Exception('Webhook signature verification failed');
        }
    }

    /**
     * Get public key for frontend
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
}

