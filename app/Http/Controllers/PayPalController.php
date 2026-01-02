<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Services\RefundService;
use Illuminate\Support\Facades\Log;
use App\Models\TrialRegistration;
use Exception;

class PayPalController extends Controller
{
    protected $paypalService;
    protected $refundService;

    public function __construct(PayPalService $paypalService, RefundService $refundService)
    {
        $this->paypalService = $paypalService;
        $this->refundService = $refundService;
    }

    /**
     * Khởi tạo thanh toán PayPal
     */
    public function checkout(Request $request)
    {
        try {
            // Debug thông tin PayPal
            Log::info('Starting PayPal checkout', [
                'client_id' => env('PAYPAL_CLIENT_ID'),
                'base_url' => env('PAYPAL_BASE_URL'),
                'client_secret_length' => strlen(env('PAYPAL_CLIENT_SECRET')),
                'return_url' => env('PAYPAL_RETURN_URL')
            ]);
            
            // Tạo giao dịch PayPal với số tiền 1.00 USD (minimum cho live environment)
            $response = $this->paypalService->createPayment(1.00);
            
            if (isset($response['id'])) {
                Log::info('PayPal order created', ['order_id' => $response['id']]);
                
                // Tìm liên kết URL để redirect
                $approvalUrl = null;
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve' || $link['rel'] === 'payer-action') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
                
                if (!$approvalUrl && isset($response['id'])) {
                    // Trong PayPal API mới, đôi khi cấu trúc links khác
                    // Sử dụng URL động dựa vào môi trường
                    $isSandbox = strpos(env('PAYPAL_BASE_URL'), 'sandbox') !== false;
                    $paypalDomain = $isSandbox ? 'https://www.sandbox.paypal.com' : 'https://www.paypal.com';
                    $approvalUrl = $paypalDomain . "/checkoutnow?token=" . $response['id'];
                }
                
                if ($approvalUrl) {
                    Log::info('Redirecting to PayPal approval page', ['url' => $approvalUrl]);
                    return redirect($approvalUrl);
                }
            }
            
            throw new Exception('PayPal payment creation failed.');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal checkout failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config_check' => [
                    'client_id_set' => !empty(env('PAYPAL_CLIENT_ID')),
                    'client_secret_set' => !empty(env('PAYPAL_CLIENT_SECRET')),
                    'base_url' => env('PAYPAL_BASE_URL'),
                    'return_url' => env('PAYPAL_RETURN_URL')
                ]
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Không thể khởi tạo thanh toán PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý callback sau khi thanh toán thành công
     */
    public function success(Request $request)
    {
        try {
            $orderId = $request->get('token');
            Log::info('PayPal success callback received', ['order_id' => $orderId]);
            
            if (!$orderId) {
                throw new Exception('Missing PayPal order ID');
            }
            
            // Lấy thông tin chi tiết về đơn hàng
            $orderDetails = $this->paypalService->getOrderDetails($orderId);
            Log::info('PayPal order details retrieved', ['status' => $orderDetails['status']]);
            
            if ($orderDetails['status'] === 'APPROVED') {
                // Capture thanh toán
                $captureResponse = $this->paypalService->capturePayment($orderId);
                Log::info('PayPal payment captured', ['capture_response' => $captureResponse]);
                
                // Extract capture ID from response
                $captureId = null;
                if (isset($captureResponse['purchase_units'][0]['payments']['captures'][0]['id'])) {
                    $captureId = $captureResponse['purchase_units'][0]['payments']['captures'][0]['id'];
                }
                
                // Cập nhật trạng thái thanh toán trong DB
                if (session()->has('trial_id')) {
                    $trialId = session('trial_id');
                    $trial = TrialRegistration::find($trialId);
                    
                    if ($trial) {
                        $trial->update([
                            'payment_completed' => true,
                            'payment_method' => 'paypal',
                            'payment_transaction_id' => $orderId,
                            'payment_capture_id' => $captureId,
                            'payment_amount' => 1.00
                        ]);
                        
                        Log::info('Trial registration payment status updated', [
                            'trial_id' => $trialId,
                            'capture_id' => $captureId
                        ]);

                        // 🎯 AUTOMATIC REFUND: Refund $1 immediately after successful capture
                        if ($captureId) {
                            Log::info('Starting automatic refund process', [
                                'trial_id' => $trialId,
                                'capture_id' => $captureId
                            ]);

                            $refundResult = $this->refundService->processTrialRefund($trial, $captureId, 1.00);
                            
                            if ($refundResult) {
                                Log::info('Automatic refund successful', [
                                    'trial_id' => $trialId,
                                    'refund_id' => $refundResult['id'],
                                    'refund_status' => $refundResult['status']
                                ]);
                            } else {
                                Log::warning('Automatic refund failed, will retry later', [
                                    'trial_id' => $trialId,
                                    'capture_id' => $captureId
                                ]);
                            }
                        }
                    }
                }
                
                return redirect()->route('trial.success')
                    ->with('success', 'Thanh toán thành công!');
            }
            
            return redirect()->route('trial.payment.options')
                ->with('error', 'Không thể hoàn tất thanh toán PayPal.');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal success callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $request->get('token'),
                'all_params' => $request->all()
            ]);
            
            return redirect()->route('trial.payment.options')
                ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán PayPal: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý hủy thanh toán
     */
    public function cancel()
    {
        return redirect()->route('trial.payment.options')
            ->with('info', 'Bạn đã hủy thanh toán PayPal.');
    }
}