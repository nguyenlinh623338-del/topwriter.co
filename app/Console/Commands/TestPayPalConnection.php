<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestPayPalConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:paypal-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal API connection and authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testing PayPal Connection...');
        $this->newLine();

        // Step 1: Check environment variables
        $this->info('📋 Step 1: Checking environment variables...');
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        $baseUrl = env('PAYPAL_BASE_URL');

        if (empty($clientId)) {
            $this->error('❌ PAYPAL_CLIENT_ID is not set');
            return 1;
        }

        if (empty($clientSecret)) {
            $this->error('❌ PAYPAL_CLIENT_SECRET is not set');
            return 1;
        }

        if (empty($baseUrl)) {
            $this->error('❌ PAYPAL_BASE_URL is not set');
            return 1;
        }

        $this->info("✅ Client ID: " . substr($clientId, 0, 10) . "...");
        $this->info("✅ Client Secret: " . substr($clientSecret, 0, 10) . "...");
        $this->info("✅ Base URL: {$baseUrl}");

        // Step 2: Test authentication
        $this->info('🔐 Step 2: Testing authentication...');
        
        try {
            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['access_token'])) {
                    $this->info('✅ Authentication successful!');
                    $this->info("   - Token Type: {$json['token_type']}");
                    $this->info("   - Expires In: {$json['expires_in']} seconds");
                    $this->info("   - Access Token: " . substr($json['access_token'], 0, 20) . "...");
                    
                    // Step 3: Test creating a payment
                    $this->info('💳 Step 3: Testing payment creation...');
                    $this->testPaymentCreation($json['access_token'], $baseUrl);
                    
                } else {
                    $this->error('❌ No access token in response');
                    $this->error('Response: ' . $response->body());
                    return 1;
                }
            } else {
                $this->error('❌ Authentication failed');
                $this->error("Status: {$response->status()}");
                $this->error("Response: {$response->body()}");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception during authentication: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('🎉 PayPal connection test completed successfully!');
        return 0;
    }

    private function testPaymentCreation($accessToken, $baseUrl)
    {
        try {
            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '1.00'
                        ]
                    ]
                ],
                'application_context' => [
                    'return_url' => 'http://localhost:8000/paypal-success',
                    'cancel_url' => 'http://localhost:8000/paypal-cancel'
                ]
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$baseUrl}/v2/checkout/orders", $payload);

            if ($response->successful()) {
                $payment = $response->json();
                $this->info('✅ Payment creation successful!');
                $this->info("   - Payment ID: {$payment['id']}");
                $this->info("   - Status: {$payment['status']}");
                
                // Find approval URL
                foreach ($payment['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $this->info("   - Approval URL: {$link['href']}");
                        break;
                    }
                }
                
                return $payment;
            } else {
                $this->error('❌ Payment creation failed');
                $this->error("Status: {$response->status()}");
                $this->error("Response: {$response->body()}");
                return null;
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception during payment creation: ' . $e->getMessage());
            return null;
        }
    }
}
