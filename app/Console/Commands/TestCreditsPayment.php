<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use App\Services\PayPalService;
use App\Services\CoinbasePaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

class TestCreditsPayment extends Command
{
    protected $signature = 'test:credits-payment {method=paypal} {--email=sb-gbnhi37819222@personal.example.com} {--password=3xINVl_<}';
    protected $description = 'Test credits payment flow with real PayPal/Coinbase';

    public function handle()
    {
        $method = $this->argument('method');
        $this->info("🧪 Testing Credits Payment Flow - Method: " . strtoupper($method));
        
        // Create test user
        $testUser = User::firstOrCreate([
            'email' => 'test.credits@example.com'
        ], [
            'name' => 'Credits Test User',
            'password' => bcrypt('password'),
            'credits' => 0
        ]);
        
        $this->info("👤 Test user created/found: {$testUser->email} (ID: {$testUser->id})");
        $this->info("💰 Current credits: {$testUser->credits}");
        
        // Login user for testing
        auth()->login($testUser);
        $this->info("🔐 User logged in for testing");
        
        if ($method === 'paypal') {
            $this->testPayPalCredits($testUser);
        } elseif ($method === 'coinbase') {
            $this->testCoinbaseCredits($testUser);
        } else {
            $this->error("❌ Invalid method. Use 'paypal' or 'coinbase'");
            return 1;
        }
        
        return 0;
    }
    
    private function testPayPalCredits($user)
    {
        $this->info("\n🔄 Testing PayPal Credits Payment...");
        
        try {
            $paypalService = app(PayPalService::class);
            
            // Test PayPal connection
            $this->info("🔗 Testing PayPal connection...");
            $paymentData = $paypalService->createPayment(600.00);
            
            if (!isset($paymentData['id'])) {
                throw new Exception('Failed to create PayPal payment');
            }
            
            $orderId = $paymentData['id'];
            $this->info("✅ PayPal order created successfully: {$orderId}");
            
            // Create transaction record
            $this->info("💾 Creating transaction record...");
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = 600.00;
            $transaction->type = 'credits';
            $transaction->status = 'pending';
            
            // Check columns and add if exist
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $transaction->payment_method = 'paypal';
                $this->info("✓ payment_method column exists");
            } else {
                $this->warn("⚠ payment_method column missing");
            }
            
            if (Schema::hasColumn('transactions', 'transaction_type')) {
                $transaction->transaction_type = 'credits';
                $this->info("✓ transaction_type column exists");
            } else {
                $this->warn("⚠ transaction_type column missing");
            }
            
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $transaction->payment_status = 'pending';
                $this->info("✓ payment_status column exists");
            } else {
                $this->warn("⚠ payment_status column missing");
            }
            
            if (Schema::hasColumn('transactions', 'credits')) {
                $transaction->credits = 30;
                $this->info("✓ credits column exists");
            } else {
                $this->warn("⚠ credits column missing");
            }
            
            if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                $transaction->payment_transaction_id = $orderId;
                $this->info("✓ payment_transaction_id column exists");
            } else {
                $this->warn("⚠ payment_transaction_id column missing");
            }
            
            $transaction->save();
            $this->info("✅ Transaction created with ID: {$transaction->id}");
            
            // Get approval URL
            $approvalUrl = null;
            if (isset($paymentData['links'])) {
                foreach ($paymentData['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
            }
            
            if ($approvalUrl) {
                $this->info("🔗 PayPal approval URL generated:");
                $this->line($approvalUrl);
                $this->info("\n📋 Manual Testing Instructions:");
                $this->info("1. Open the URL above in your browser");
                $this->info("2. Login with: " . $this->option('email'));
                $this->info("3. Password: " . $this->option('password'));
                $this->info("4. Complete the payment");
                $this->info("5. Check if credits are added to user account");
                
                // Simulate success callback (for testing)
                if ($this->confirm('Simulate successful payment callback?')) {
                    $this->simulatePayPalSuccess($transaction, $orderId, $user);
                }
            } else {
                $this->error("❌ PayPal approval URL not found");
            }
            
        } catch (Exception $e) {
            $this->error("❌ PayPal Credits Test Failed: " . $e->getMessage());
            Log::error('PayPal Credits Test Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    private function simulatePayPalSuccess($transaction, $orderId, $user)
    {
        $this->info("\n🎯 Simulating PayPal success callback...");
        
        try {
            // Update transaction status
            $updateData = ['status' => 'completed'];
            
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $updateData['payment_status'] = 'completed';
            }
            if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                $updateData['payment_transaction_id'] = $orderId;
            }
            if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                $updateData['payment_completed_at'] = now();
            }
            
            $transaction->update($updateData);
            $this->info("✅ Transaction updated to completed");
            
            // Add credits to user
            $oldCredits = $user->credits;
            $user->credits += 30;
            $user->save();
            
            $this->info("💰 Credits added successfully:");
            $this->info("   Previous: {$oldCredits} credits");
            $this->info("   Added: 30 credits");
            $this->info("   New total: {$user->credits} credits");
            
            $this->info("✅ Credits payment simulation completed successfully!");
            
        } catch (Exception $e) {
            $this->error("❌ Success simulation failed: " . $e->getMessage());
        }
    }
    
    private function testCoinbaseCredits($user)
    {
        $this->info("\n🔄 Testing Coinbase Credits Payment...");
        
        try {
            $coinbaseService = app(CoinbasePaymentService::class);
            
            // Test Coinbase connection
            $this->info("🔗 Testing Coinbase connection...");
            $charge = $coinbaseService->createCreditsPayment(600.00);
            
            if (!isset($charge['id'])) {
                throw new Exception('Failed to create Coinbase charge');
            }
            
            $chargeId = $charge['id'];
            $this->info("✅ Coinbase charge created successfully: {$chargeId}");
            
            // Create transaction record
            $this->info("💾 Creating transaction record...");
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = 600.00;
            $transaction->type = 'credits';
            $transaction->status = 'pending';
            
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $transaction->payment_method = 'coinbase';
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
                $transaction->payment_transaction_id = $chargeId;
            }
            
            $transaction->save();
            $this->info("✅ Transaction created with ID: {$transaction->id}");
            
            if (isset($charge['hosted_url'])) {
                $this->info("🔗 Coinbase payment URL:");
                $this->line($charge['hosted_url']);
                $this->info("\n📋 Manual Testing Instructions:");
                $this->info("1. Open the URL above in your browser");
                $this->info("2. Complete the crypto payment");
                $this->info("3. Check if credits are added to user account");
                
                // Simulate success callback
                if ($this->confirm('Simulate successful payment callback?')) {
                    $this->simulateCoinbaseSuccess($transaction, $user);
                }
            }
            
        } catch (Exception $e) {
            $this->error("❌ Coinbase Credits Test Failed: " . $e->getMessage());
            Log::error('Coinbase Credits Test Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    private function simulateCoinbaseSuccess($transaction, $user)
    {
        $this->info("\n🎯 Simulating Coinbase success callback...");
        
        try {
            // Update transaction status
            $updateData = ['status' => 'completed'];
            
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $updateData['payment_status'] = 'completed';
            }
            if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                $updateData['payment_completed_at'] = now();
            }
            
            $transaction->update($updateData);
            $this->info("✅ Transaction updated to completed");
            
            // Add credits to user
            $oldCredits = $user->credits;
            $user->credits += 30;
            $user->save();
            
            $this->info("💰 Credits added successfully:");
            $this->info("   Previous: {$oldCredits} credits");
            $this->info("   Added: 30 credits");
            $this->info("   New total: {$user->credits} credits");
            
            $this->info("✅ Credits payment simulation completed successfully!");
            
        } catch (Exception $e) {
            $this->error("❌ Success simulation failed: " . $e->getMessage());
        }
    }
} 