<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use App\Services\PayPalService;
use App\Services\CoinbasePaymentService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class TestCreditsFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:credits-flow {--method=paypal : Payment method (paypal or coinbase)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test credits payment flow using successful trial payment formula';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Testing Credits Payment Flow...');
        $this->newLine();

        $method = $this->option('method');
        
        try {
            if ($method === 'coinbase') {
                $this->testCoinbaseCredits();
            } else {
                $this->testPayPalCredits();
            }
        } catch (Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function testPayPalCredits()
    {
        $this->info('💳 Testing PayPal Credits Flow...');
        
        // Step 1: Create test user
        $this->info('👤 Step 1: Creating test user...');
        $testEmail = 'credits_paypal_test_' . time() . '@example.com';
        $user = User::create([
            'name' => 'Credits PayPal Test User',
            'email' => $testEmail,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'credits' => 0,
        ]);
        $this->info("✅ User created: {$user->email} (ID: {$user->id})");

        // Step 2: Test PayPal service
        $this->info('💳 Step 2: Testing PayPal credits payment creation...');
        $paypalService = new PayPalService();
        
        // Using the successful trial payment formula
        $paymentData = $paypalService->createPayment(600.00);
        
        if (!isset($paymentData['id'])) {
            throw new Exception('Failed to create PayPal credits payment');
        }
        
        $orderId = $paymentData['id'];
        $this->info("✅ PayPal credits payment created: {$orderId}");

        // Step 3: Create transaction record
        $this->info('📝 Step 3: Creating transaction record...');
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = 600.00;
        $transaction->payment_method = 'paypal';
        $transaction->type = 'credits';
        $transaction->transaction_type = 'credits';
        $transaction->payment_status = 'pending';
        $transaction->status = 'pending';
        $transaction->credits = 30;
        $transaction->payment_transaction_id = $orderId;
        $transaction->save();
        
        $this->info("✅ Transaction created (ID: {$transaction->id})");

        // Step 4: Display results
        $this->displayResults($user, $transaction, $paymentData);
    }

    private function testCoinbaseCredits()
    {
        $this->info('🪙 Testing Coinbase Credits Flow...');
        
        // Step 1: Create test user
        $this->info('👤 Step 1: Creating test user...');
        $testEmail = 'credits_coinbase_test_' . time() . '@example.com';
        $user = User::create([
            'name' => 'Credits Coinbase Test User',
            'email' => $testEmail,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'credits' => 0,
        ]);
        $this->info("✅ User created: {$user->email} (ID: {$user->id})");

        // Step 2: Test Coinbase service
        $this->info('🪙 Step 2: Testing Coinbase credits payment creation...');
        
        // Temporarily set auth user for Coinbase service
        auth()->login($user);
        
        $coinbaseService = new CoinbasePaymentService();
        $chargeData = $coinbaseService->createCreditsPayment(600.00);
        
        if (!isset($chargeData['id'])) {
            throw new Exception('Failed to create Coinbase credits payment');
        }
        
        $chargeId = $chargeData['id'];
        $this->info("✅ Coinbase credits charge created: {$chargeId}");

        // Step 3: Create transaction record
        $this->info('📝 Step 3: Creating transaction record...');
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = 600.00;
        $transaction->payment_method = 'coinbase';
        $transaction->type = 'credits';
        $transaction->transaction_type = 'credits';
        $transaction->payment_status = 'pending';
        $transaction->status = 'pending';
        $transaction->credits = 30;
        $transaction->payment_transaction_id = $chargeId;
        $transaction->save();
        
        $this->info("✅ Transaction created (ID: {$transaction->id})");

        // Step 4: Display results
        $this->displayResults($user, $transaction, $chargeData);
    }

    private function displayResults($user, $transaction, $paymentData)
    {
        $this->newLine();
        $this->info('📊 RESULTS:');
        $this->info('=' . str_repeat('=', 50));
        
        $this->table(
            ['Field', 'Value', 'Status'],
            [
                ['User Created', $user->email, '✅'],
                ['User Credits', $user->credits, '✅'],
                ['Transaction ID', $transaction->id, '✅'],
                ['Payment Method', $transaction->payment_method, '✅'],
                ['Amount', '$' . $transaction->amount, '✅'],
                ['Credits to Add', $transaction->credits, '✅'],
                ['Payment ID', $transaction->payment_transaction_id, '✅'],
                ['Transaction Status', $transaction->status, '✅'],
            ]
        );

        if ($transaction->payment_method === 'paypal') {
            $this->info('💳 PayPal Payment Details:');
            if (isset($paymentData['links'])) {
                foreach ($paymentData['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $this->info("   - Approval URL: {$link['href']}");
                        break;
                    }
                }
            }
        } else {
            $this->info('🪙 Coinbase Payment Details:');
            if (isset($paymentData['hosted_url'])) {
                $this->info("   - Checkout URL: {$paymentData['hosted_url']}");
            }
        }

        $this->newLine();
        $this->info('🎉 Credits payment flow test completed successfully!');
        $this->info('💡 This uses the same successful formula as trial payments (no refund for credits).');
        
        $this->newLine();
        $this->warn('⚠️  Next Steps:');
        $this->warn('   1. Test the payment approval flow manually');
        $this->warn('   2. Verify credits are added after successful payment');
        $this->warn('   3. Check transaction status updates correctly');
    }
}
