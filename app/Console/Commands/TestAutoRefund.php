<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RefundService;
use App\Models\TrialRegistration;
use Illuminate\Support\Facades\Log;

class TestAutoRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:auto-refund {--trial-id= : Specific trial ID to refund} {--capture-id= : Specific capture ID to refund}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test automatic refund functionality for trial payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Testing Auto Refund Functionality...');
        $this->newLine();

        $refundService = app(RefundService::class);

        // Option 1: Test specific trial ID
        if ($trialId = $this->option('trial-id')) {
            $this->testSpecificTrial($refundService, $trialId);
            return 0;
        }

        // Option 2: Test with specific capture ID
        if ($captureId = $this->option('capture-id')) {
            $this->testSpecificCapture($refundService, $captureId);
            return 0;
        }

        // Option 3: Test all pending refunds
        $this->testPendingRefunds($refundService);
        return 0;
    }

    private function testSpecificTrial(RefundService $refundService, $trialId)
    {
        $this->info("🎯 Testing refund for trial ID: {$trialId}");
        
        $trial = TrialRegistration::find($trialId);
        if (!$trial) {
            $this->error("❌ Trial registration not found: {$trialId}");
            return;
        }

        $this->displayTrialInfo($trial);

        if (!$trial->payment_capture_id) {
            $this->error("❌ No capture ID found for this trial");
            return;
        }

        if ($trial->payment_refunded) {
            $this->warn("⚠️ Trial already refunded");
            $this->info("   - Refund ID: {$trial->refund_transaction_id}");
            $this->info("   - Refunded at: {$trial->refund_processed_at}");
            return;
        }

        // Process refund
        $this->info("💰 Processing refund...");
        $refundResult = $refundService->processTrialRefund($trial, $trial->payment_capture_id, $trial->payment_amount);

        if ($refundResult) {
            $this->info("✅ Refund successful!");
            $this->info("   - Refund ID: {$refundResult['id']}");
            $this->info("   - Status: {$refundResult['status']}");
            if (isset($refundResult['amount'])) {
                $this->info("   - Amount: {$refundResult['amount']['value']} {$refundResult['amount']['currency_code']}");
            }
        } else {
            $this->error("❌ Refund failed");
        }
    }

    private function testSpecificCapture(RefundService $refundService, $captureId)
    {
        $this->info("🎯 Testing refund for capture ID: {$captureId}");

        // Find trial by capture ID
        $trial = TrialRegistration::where('payment_capture_id', $captureId)->first();
        
        if (!$trial) {
            $this->error("❌ No trial found with capture ID: {$captureId}");
            return;
        }

        $this->testSpecificTrial($refundService, $trial->id);
    }

    private function testPendingRefunds(RefundService $refundService)
    {
        $this->info("🔍 Finding pending refunds...");
        
        $pendingRefunds = $refundService->getPendingRefunds();
        
        if ($pendingRefunds->isEmpty()) {
            $this->warn("⚠️ No pending refunds found");
            
            // Show recent completed payments
            $this->info("📋 Recent completed payments:");
            $recentPayments = TrialRegistration::where('payment_completed', true)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            if ($recentPayments->isEmpty()) {
                $this->info("   No recent payments found");
            } else {
                $this->table(
                    ['ID', 'User', 'Payment Method', 'Amount', 'Capture ID', 'Refunded', 'Updated'],
                    $recentPayments->map(function ($trial) {
                        return [
                            $trial->id,
                            $trial->user->email ?? 'N/A',
                            $trial->payment_method,
                            '$' . $trial->payment_amount,
                            $trial->payment_capture_id ?? 'N/A',
                            $trial->payment_refunded ? '✅' : '❌',
                            $trial->updated_at->diffForHumans()
                        ];
                    })->toArray()
                );
            }
            return;
        }

        $this->info("Found {$pendingRefunds->count()} pending refunds:");
        $this->newLine();

        foreach ($pendingRefunds as $trial) {
            $this->info("🔄 Processing trial ID: {$trial->id}");
            $this->displayTrialInfo($trial);

            $refundResult = $refundService->processTrialRefund($trial, $trial->payment_capture_id, $trial->payment_amount);

            if ($refundResult) {
                $this->info("✅ Refund successful: {$refundResult['id']}");
            } else {
                $this->error("❌ Refund failed");
            }
            
            $this->newLine();
        }
    }

    private function displayTrialInfo(TrialRegistration $trial)
    {
        $this->table(
            ['Field', 'Value'],
            [
                ['Trial ID', $trial->id],
                ['User', $trial->user->email ?? 'N/A'],
                ['Payment Method', $trial->payment_method],
                ['Payment Amount', '$' . $trial->payment_amount],
                ['Transaction ID', $trial->payment_transaction_id],
                ['Capture ID', $trial->payment_capture_id ?? 'N/A'],
                ['Payment Completed', $trial->payment_completed ? '✅' : '❌'],
                ['Refunded', $trial->payment_refunded ? '✅' : '❌'],
                ['Refund ID', $trial->refund_transaction_id ?? 'N/A'],
                ['Created', $trial->created_at->diffForHumans()],
                ['Updated', $trial->updated_at->diffForHumans()],
            ]
        );
    }
}
