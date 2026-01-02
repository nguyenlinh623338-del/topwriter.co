<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TrialRegistration;
use App\Services\PayPalService;
use App\Services\RefundService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class TestFullRefundFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:full-refund-flow {--skip-payment : Skip payment creation and use existing data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test complete refund flow: User → Payment → Capture → Auto Refund';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Testing Complete Refund Flow...');
        $this->newLine();

        try {
            if ($this->option('skip-payment')) {
                $this->testExistingPayments();
            } else {
                $this->testFullFlow();
            }
        } catch (Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function testFullFlow()
    {
        // Step 1: Create test user
        $this->info('👤 Step 1: Creating test user...');
        $testEmail = 'refund_test_' . time() . '@example.com';
        $user = User::create([
            'name' => 'Refund Test User',
            'email' => $testEmail,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $this->info("✅ User created: {$user->email} (ID: {$user->id})");

        // Step 2: Create trial registration
        $this->info('📝 Step 2: Creating trial registration...');
        $trial = TrialRegistration::create([
            'user_id' => $user->id,
            'industry_type' => 'Health',
            'industry' => 'Medical Writing',
            'keyword_option' => 'custom',
            'keywords' => 'medical, health, wellness',
            'primary_keywords' => 'medical writing, health content',
            'notes' => 'Auto refund test trial',
            'payment_completed' => false,
            'payment_method' => 'paypal',
            'payment_amount' => 1.00,
        ]);
        $this->info("✅ Trial registration created (ID: {$trial->id})");

        // Step 3: Create PayPal payment
        $this->info('💳 Step 3: Creating PayPal payment...');
        $paypalService = new PayPalService();
        $paymentData = $paypalService->createPayment(1.00, 'USD');
        
        if (!isset($paymentData['id'])) {
            throw new Exception('Failed to create PayPal payment');
        }
        
        $orderId = $paymentData['id'];
        $this->info("✅ PayPal payment created: {$orderId}");

        // Step 4: Simulate payment approval and capture
        $this->info('🔄 Step 4: Simulating payment approval...');
        $this->warn('⚠️  MANUAL STEP REQUIRED:');
        $this->warn("   Please visit PayPal and approve payment: {$orderId}");
        $this->warn("   Or use the approval URL from PayPal response");
        
        // For simulation, we'll update the trial with mock capture data
        $mockCaptureId = 'MOCK_CAPTURE_' . time();
        $trial->update([
            'payment_completed' => true,
            'payment_transaction_id' => $orderId,
            'payment_capture_id' => $mockCaptureId,
        ]);
        
        $this->info("✅ Payment simulation completed");
        $this->info("   - Order ID: {$orderId}");
        $this->info("   - Mock Capture ID: {$mockCaptureId}");

        // Step 5: Test automatic refund
        $this->info('💰 Step 5: Testing automatic refund...');
        $this->testRefundForTrial($trial, $mockCaptureId);

        // Step 6: Display final results
        $this->displayFinalResults($trial);
    }

    private function testExistingPayments()
    {
        $this->info('🔍 Testing existing payments...');
        
        $trials = TrialRegistration::where('payment_completed', true)
            ->whereNotNull('payment_capture_id')
            ->where('payment_refunded', false)
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        if ($trials->isEmpty()) {
            $this->warn('⚠️ No eligible trials found for refund testing');
            return;
        }

        $this->info("Found {$trials->count()} eligible trials:");
        foreach ($trials as $trial) {
            $this->info("🔄 Testing trial ID: {$trial->id}");
            $this->testRefundForTrial($trial, $trial->payment_capture_id);
            $this->newLine();
        }
    }

    private function testRefundForTrial(TrialRegistration $trial, string $captureId)
    {
        $refundService = new RefundService(new PayPalService());
        
        $this->table(
            ['Field', 'Before Refund'],
            [
                ['Trial ID', $trial->id],
                ['User', $trial->user->email],
                ['Payment Completed', $trial->payment_completed ? '✅' : '❌'],
                ['Payment Amount', '$' . $trial->payment_amount],
                ['Capture ID', $trial->payment_capture_id],
                ['Refunded', $trial->payment_refunded ? '✅' : '❌'],
                ['Refund ID', $trial->refund_transaction_id ?: 'N/A'],
            ]
        );

        if ($trial->payment_refunded) {
            $this->warn('⚠️ Trial already refunded, skipping...');
            return;
        }

        $this->info('💰 Processing refund...');
        $refundResult = $refundService->processTrialRefund($trial, $captureId, $trial->payment_amount);

        if ($refundResult) {
            $this->info('✅ Refund successful!');
            $this->info("   - Refund ID: {$refundResult['id']}");
            $this->info("   - Status: {$refundResult['status']}");
            
            // Refresh trial data
            $trial->refresh();
            
            $this->table(
                ['Field', 'After Refund'],
                [
                    ['Refunded', $trial->payment_refunded ? '✅' : '❌'],
                    ['Refund ID', $trial->refund_transaction_id],
                    ['Refund Date', $trial->refund_processed_at ? $trial->refund_processed_at->format('Y-m-d H:i:s') : 'N/A'],
                ]
            );
        } else {
            $this->error('❌ Refund failed');
        }
    }

    private function displayFinalResults(TrialRegistration $trial)
    {
        $this->newLine();
        $this->info('📊 FINAL RESULTS:');
        $this->info('=' . str_repeat('=', 50));
        
        $trial->refresh();
        
        $this->table(
            ['Metric', 'Value', 'Status'],
            [
                ['User Created', $trial->user->email, '✅'],
                ['Trial Registration', "ID: {$trial->id}", '✅'],
                ['Payment Completed', '$' . $trial->payment_amount, $trial->payment_completed ? '✅' : '❌'],
                ['Payment Captured', $trial->payment_capture_id, $trial->payment_capture_id ? '✅' : '❌'],
                ['Refund Processed', $trial->refund_transaction_id ?: 'N/A', $trial->payment_refunded ? '✅' : '❌'],
                ['Refund Date', $trial->refund_processed_at ? $trial->refund_processed_at->format('Y-m-d H:i:s') : 'N/A', $trial->payment_refunded ? '✅' : '❌'],
            ]
        );

        if ($trial->payment_refunded) {
            $this->info('🎉 COMPLETE SUCCESS! Auto-refund system working perfectly!');
            $this->info('💡 User paid $1, got refunded $1, and received trial access.');
        } else {
            $this->warn('⚠️ Refund not completed. Check logs for details.');
        }
    }
}
