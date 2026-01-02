<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TrialRegistration;
use App\Services\PayPalService;
use App\Services\PythonApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class TestPaymentFlow extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:payment-flow {--refund : Test refund functionality}';

    /**
     * The console command description.
     */
    protected $description = 'Test the complete payment flow including PayPal integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Payment Flow...');
        $this->newLine();

        try {
            // Step 1: Create test user
            $this->info('📝 Step 1: Creating test user...');
            $user = $this->createTestUser();
            $this->info("✅ User created: {$user->email} (ID: {$user->id})");

            // Step 2: Create trial registration
            $this->info('📋 Step 2: Creating trial registration...');
            $trial = $this->createTrialRegistration($user);
            $this->info("✅ Trial registration created (ID: {$trial->id})");

            // Step 3: Test PayPal Service
            $this->info('💳 Step 3: Testing PayPal service...');
            try {
                $paypalService = new PayPalService();
                $this->info('✅ PayPal service initialized successfully');
            } catch (Exception $e) {
                $this->error('❌ PayPal service initialization failed: ' . $e->getMessage());
                
                // Let's try direct HTTP call instead
                $this->info('🔄 Trying direct HTTP call...');
                $clientId = env('PAYPAL_CLIENT_ID');
                $clientSecret = env('PAYPAL_CLIENT_SECRET');
                $baseUrl = env('PAYPAL_BASE_URL');
                
                $response = Http::asForm()
                    ->withBasicAuth($clientId, $clientSecret)
                    ->post("{$baseUrl}/v1/oauth2/token", [
                        'grant_type' => 'client_credentials'
                    ]);
                
                if ($response->successful()) {
                    $this->info('✅ Direct HTTP call successful, PayPal API is working');
                    throw $e; // Re-throw to see the original error
                } else {
                    $this->error('❌ Direct HTTP call also failed');
                    throw $e;
                }
            }

            // Step 4: Create PayPal payment
            $this->info('🔄 Step 4: Creating PayPal payment...');
            $payment = $paypalService->createPayment(1.00);
            
            if (isset($payment['id'])) {
                $this->info("✅ PayPal payment created: {$payment['id']}");
                
                // Extract approval URL
                $approvalUrl = null;
                foreach ($payment['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
                
                if ($approvalUrl) {
                    $this->info("🔗 Approval URL: {$approvalUrl}");
                } else {
                    $this->warn("⚠️ No approval URL found in response");
                }

                // Step 5: Simulate payment completion
                $this->info('✅ Step 5: Simulating payment completion...');
                $this->simulatePaymentSuccess($trial, $payment['id']);

                // Step 6: Test refund if requested
                if ($this->option('refund')) {
                    $this->info('💰 Step 6: Testing refund functionality...');
                    $this->testRefund($paypalService, $payment['id']);
                }

            } else {
                $this->error('❌ Failed to create PayPal payment');
                $this->error('Response: ' . json_encode($payment, JSON_PRETTY_PRINT));
                return 1;
            }

            $this->newLine();
            $this->info('🎉 Payment flow test completed successfully!');
            
            // Display summary
            $this->displaySummary($user, $trial, $payment);

        } catch (Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    private function createTestUser()
    {
        $email = 'test_' . time() . '@example.com';
        
        return User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => Hash::make('password123'),
            'credits' => 0,
            'trial_used' => false
        ]);
    }

    private function createTrialRegistration(User $user)
    {
        return TrialRegistration::create([
            'user_id' => $user->id,
            'industry_type' => 'health',
            'industry' => 'Medical Devices',
            'keyword_option' => 'provided',
            'keywords' => 'medical devices, healthcare technology, patient monitoring',
            'primary_keywords' => '',
            'competitor_links' => ['https://example.com/competitor1', 'https://example.com/competitor2'],
            'notes' => 'Test trial registration for payment flow testing',
            'direct_posting' => false,
            'website_url' => 'https://test-website.com',
            'website_username' => 'testuser',
            'website_password' => encrypt('testpass123'),
            'login_url' => 'https://test-website.com/login',
            'payment_completed' => false,
            'payment_method' => null,
            'payment_amount' => 0
        ]);
    }

    private function simulatePaymentSuccess(TrialRegistration $trial, string $paymentId)
    {
        // Simulate what happens in PayPalController@success
        $trial->update([
            'payment_completed' => true,
            'payment_method' => 'paypal',
            'payment_transaction_id' => $paymentId,
            'payment_amount' => 1.00
        ]);

        $this->info("✅ Trial registration updated with payment info");
        $this->info("   - Payment ID: {$paymentId}");
        $this->info("   - Amount: $1.00 USD");
        $this->info("   - Method: PayPal");
    }

    private function testRefund(PayPalService $paypalService, string $paymentId)
    {
        try {
            // First we need to get order details and capture it
            $this->info('📋 Getting order details...');
            $orderDetails = $paypalService->getOrderDetails($paymentId);
            
            if ($orderDetails['status'] === 'CREATED') {
                $this->warn('⚠️ Payment is still in CREATED status (not approved by user)');
                $this->info('💡 In real scenario, user would approve payment first');
                return;
            }

            if ($orderDetails['status'] === 'APPROVED') {
                $this->info('✅ Order is approved, capturing payment...');
                $captureResponse = $paypalService->capturePayment($paymentId);
                
                if (isset($captureResponse['purchase_units'][0]['payments']['captures'][0]['id'])) {
                    $captureId = $captureResponse['purchase_units'][0]['payments']['captures'][0]['id'];
                    $this->info("✅ Payment captured: {$captureId}");
                    
                    // Now test refund
                    $this->info('💰 Initiating refund...');
                    $refundResponse = $paypalService->refundPayment($captureId, 1.00);
                    
                    if (isset($refundResponse['id'])) {
                        $this->info("✅ Refund successful: {$refundResponse['id']}");
                        $this->info("   - Status: {$refundResponse['status']}");
                        $this->info("   - Amount: {$refundResponse['amount']['value']} {$refundResponse['amount']['currency_code']}");
                    } else {
                        $this->error('❌ Refund failed');
                        $this->error('Response: ' . json_encode($refundResponse, JSON_PRETTY_PRINT));
                    }
                } else {
                    $this->error('❌ Failed to capture payment');
                }
            } else {
                $this->warn("⚠️ Order status is: {$orderDetails['status']}");
            }

        } catch (Exception $e) {
            $this->error('❌ Refund test failed: ' . $e->getMessage());
        }
    }

    private function displaySummary(User $user, TrialRegistration $trial, array $payment)
    {
        $this->newLine();
        $this->info('📊 TEST SUMMARY');
        $this->info('================');
        $this->table(
            ['Component', 'Status', 'Details'],
            [
                ['User Creation', '✅ Success', "ID: {$user->id}, Email: {$user->email}"],
                ['Trial Registration', '✅ Success', "ID: {$trial->id}, Industry: {$trial->industry}"],
                ['PayPal Service', '✅ Success', 'Sandbox environment'],
                ['Payment Creation', '✅ Success', "Payment ID: {$payment['id']}"],
                ['Database Update', '✅ Success', 'Trial marked as paid'],
            ]
        );

        $this->newLine();
        $this->info('🔗 USEFUL LINKS:');
        $this->info("   - PayPal Sandbox: https://www.sandbox.paypal.com");
        $this->info("   - Test Account: sb-gbnhi37819222@personal.example.com");
        $this->info("   - Laravel Logs: tail -f storage/logs/laravel.log");

        $this->newLine();
        $this->info('💡 NEXT STEPS:');
        $this->info('   1. Check PayPal sandbox dashboard for the payment');
        $this->info('   2. Test manual approval in PayPal sandbox');
        $this->info('   3. Run with --refund flag to test refund functionality');
        $this->info('   4. Implement auto-refund after successful capture');
    }
}
