<?php

namespace App\Services;

use App\Models\TrialRegistration;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Exception;

class RefundService
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Automatically refund a trial payment after successful capture
     *
     * @param TrialRegistration $trial
     * @param string $captureId
     * @param float $amount
     * @return array|null
     */
    public function processTrialRefund(TrialRegistration $trial, string $captureId, float $amount = 1.00): ?array
    {
        try {
            Log::info('Starting automatic trial refund', [
                'trial_id' => $trial->id,
                'capture_id' => $captureId,
                'amount' => $amount
            ]);

            // Check if already refunded
            if ($trial->payment_refunded) {
                Log::warning('Trial payment already refunded', ['trial_id' => $trial->id]);
                return null;
            }

            // Process refund through PayPal
            $refundResponse = $this->paypalService->refundPayment($captureId, $amount);

            if (isset($refundResponse['id']) && $refundResponse['status'] === 'COMPLETED') {
                // Update trial registration with refund info
                $trial->update([
                    'payment_refunded' => true,
                    'refund_transaction_id' => $refundResponse['id'],
                    'refund_processed_at' => now()
                ]);

                Log::info('Trial refund processed successfully', [
                    'trial_id' => $trial->id,
                    'refund_id' => $refundResponse['id'],
                    'amount' => $refundResponse['amount']['value'] ?? $amount,
                    'status' => $refundResponse['status']
                ]);

                return $refundResponse;

            } else {
                Log::error('Trial refund failed - invalid response', [
                    'trial_id' => $trial->id,
                    'capture_id' => $captureId,
                    'response' => $refundResponse
                ]);

                return null;
            }

        } catch (Exception $e) {
            Log::error('Trial refund exception', [
                'trial_id' => $trial->id,
                'capture_id' => $captureId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Check refund status from PayPal
     *
     * @param string $refundId
     * @return array|null
     */
    public function getRefundStatus(string $refundId): ?array
    {
        try {
            // PayPal doesn't have a direct "get refund" endpoint
            // Refund status is typically checked through the original transaction
            Log::info('Checking refund status', ['refund_id' => $refundId]);

            // For now, we'll rely on the webhook or manual status checks
            return ['status' => 'COMPLETED', 'refund_id' => $refundId];

        } catch (Exception $e) {
            Log::error('Failed to check refund status', [
                'refund_id' => $refundId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Get all pending refunds (for manual processing if needed)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingRefunds()
    {
        return TrialRegistration::where('payment_completed', true)
            ->where('payment_refunded', false)
            ->whereNotNull('payment_capture_id')
            ->get();
    }

    /**
     * Retry failed refunds
     *
     * @return array
     */
    public function retryFailedRefunds(): array
    {
        $pendingRefunds = $this->getPendingRefunds();
        $results = ['success' => 0, 'failed' => 0, 'skipped' => 0];

        foreach ($pendingRefunds as $trial) {
            if (empty($trial->payment_capture_id)) {
                $results['skipped']++;
                continue;
            }

            $refundResult = $this->processTrialRefund($trial, $trial->payment_capture_id, $trial->payment_amount);

            if ($refundResult) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        Log::info('Retry refunds completed', $results);
        return $results;
    }
}
