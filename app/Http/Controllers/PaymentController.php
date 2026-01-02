<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Session;
use App\Models\Payment;
use App\Models\TrialWriting;

class PaymentController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Hiển thị trang các tùy chọn thanh toán để mua credits
     */
    public function showOptions()
    {
        return view('payment.options');
    }

    /**
     * Xử lý thanh toán PayPal cho 30 credit
     */
    public function processPayPal(Request $request)
    {
        try {
            $paypalService = new PayPalService();
            
            // Thiết lập payload dữ liệu thanh toán
            $paymentData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'description' => '30 Credit Package',
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '600.00'
                        ]
                    ]
                ],
                'application_context' => [
                    'return_url' => url('/payment-success'),
                    'cancel_url' => url('/payment-cancel')
                ]
            ];
            
            // Sử dụng phương thức createPayment với dữ liệu mới
            $payment = $paypalService->createPayment($paymentData);
            
            // Log thông tin thanh toán đã tạo
            Log::info('PayPal payment created', [
                'payment_id' => $payment['id'] ?? 'unknown',
                'user_id' => Auth::id()
            ]);
            
            // Tìm URL chuyển hướng tới trang thanh toán PayPal
            $approvalUrl = null;
            foreach ($payment['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }
            
            if (!$approvalUrl) {
                throw new \Exception('Không tìm thấy URL phê duyệt trong phản hồi PayPal');
            }
            
            // Lưu thông tin thanh toán vào session để sử dụng sau khi thanh toán thành công
            Session::put('paypal_payment_id', $payment['id']);
            Session::put('payment_type', 'credit_package');
            
            // Chuyển hướng người dùng đến trang thanh toán PayPal
            return redirect($approvalUrl);
        } catch (\Exception $e) {
            Log::error('PayPal payment process error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()
                ->route('payment.options')
                ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý khi thanh toán PayPal thành công
     */
    public function successPayPal(Request $request)
    {
        try {
            $paymentId = Session::get('paypal_payment_id');
            $paymentType = Session::get('payment_type');
            
            if (!$paymentId || $paymentType !== 'credit_package') {
                throw new \Exception('Thông tin thanh toán không hợp lệ');
            }
            
            $paypalService = new PayPalService();
            
            // Lấy thông tin chi tiết đơn hàng
            $orderDetails = $paypalService->getOrderDetails($paymentId);
            Log::info('PayPal order details retrieved', [
                'order_id' => $paymentId,
                'status' => $orderDetails['status'] ?? 'unknown'
            ]);
            
            // Nếu đơn hàng chưa được thanh toán, tiến hành capture
            if (($orderDetails['status'] ?? '') === 'APPROVED') {
                $captureResult = $paypalService->capturePayment($paymentId);
                Log::info('PayPal payment captured', [
                    'capture_id' => $captureResult['id'] ?? 'unknown',
                    'status' => $captureResult['status'] ?? 'unknown'
                ]);
                
                if (($captureResult['status'] ?? '') !== 'COMPLETED') {
                    throw new \Exception('Thanh toán không thành công');
                }
                
                // Lưu thông tin giao dịch vào cơ sở dữ liệu
                $trialWriting = new TrialWriting();
                $trialWriting->user_id = Auth::id();
                $trialWriting->payment_status = 'completed';
                $trialWriting->payment_method = 'paypal';
                $trialWriting->payment_transaction_id = $paymentId;
                $trialWriting->payment_amount = 600.00;
                $trialWriting->save();
                
                // Cập nhật số credit cho người dùng
                $user = User::find(Auth::id());
                if ($user) {
                    $user->credits += 30;
                    $user->save();
                    
                    Log::info('Credits added to user account', [
                        'user_id' => $user->id,
                        'credits_added' => 30,
                        'total_credits' => $user->credits
                    ]);
                }
                
                // Xóa thông tin thanh toán khỏi session
                Session::forget(['paypal_payment_id', 'payment_type']);
                
                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Thanh toán thành công! 30 credit đã được thêm vào tài khoản của bạn.');
            }
            
            throw new \Exception('Đơn hàng không ở trạng thái APPROVED');
        } catch (\Exception $e) {
            Log::error('PayPal success callback error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()
                ->route('dashboard')
                ->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý khi thanh toán PayPal bị hủy
     */
    public function cancelPayPal()
    {
        // Xóa thông tin thanh toán khỏi session
        Session::forget(['paypal_payment_id', 'payment_type']);
        
        return redirect()
            ->route('dashboard')
            ->with('info', 'Bạn đã hủy quá trình thanh toán.');
    }
}
