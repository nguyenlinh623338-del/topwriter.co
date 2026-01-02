<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoinbasePaymentService;
use Illuminate\Support\Facades\Log;
use App\Models\TrialRegistration;

class CoinbasePaymentController extends Controller
{
    protected $coinbase;

    public function __construct(CoinbasePaymentService $coinbase)
    {
        $this->coinbase = $coinbase;
    }

    /**
     * Checkout with Coinbase
     */
    public function checkout()
    {
        try {
            Log::info('Starting Coinbase checkout');
            
            // Kiểm tra xem user đã đăng nhập chưa
            if (!auth()->check()) {
                Log::warning('User not logged in during Coinbase checkout');
                return redirect()->route('try-writing')->with('error', 'Vui lòng đăng nhập trước khi thanh toán.');
            }

            // Số tiền tối thiểu để test (0.10 USD)
            $minAmount = 0.10;
            
            // Create a Coinbase charge with minimal amount
            $charge = $this->coinbase->createPayment($minAmount);
            Log::info('Coinbase charge created', ['charge_id' => $charge['id'] ?? 'unknown']);

            if (isset($charge['hosted_url'])) {
                Log::info('Redirecting to Coinbase checkout page', ['url' => $charge['hosted_url']]);
                return redirect()->away($charge['hosted_url']);
            }

            Log::error('Coinbase checkout URL not found', ['charge' => $charge]);
            return back()->with('error', 'Không tìm thấy liên kết thanh toán Coinbase.');
        } catch (\Exception $e) {
            Log::error('Coinbase Charge Creation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Đã xảy ra lỗi khi tạo đơn hàng Coinbase. Vui lòng thử lại.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        try {
            Log::info('Coinbase success callback received');
            
            // Cập nhật trạng thái thanh toán nếu có trial_id trong session
            if (session()->has('trial_id')) {
                $trialId = session('trial_id');
                $trial = TrialRegistration::find($trialId);
                if ($trial) {
                    $trial->payment_completed = true;
                    $trial->payment_method = 'crypto';
                    $trial->payment_amount = 0.10; // Số tiền tối thiểu
                    $trial->save();
                    
                    Log::info('Trial registration payment status updated', ['trial_id' => $trialId]);
                } else {
                    Log::warning('Trial registration not found', ['trial_id' => $trialId]);
                }
            } else {
                Log::warning('No trial_id found in session during Coinbase success');
            }
            
            // Payment successful, redirect to trial.success page
            return redirect()->route('trial.success')->with('success', 'Thanh toán thành công! Gói dùng thử của bạn đã được kích hoạt.');
        } catch (\Exception $e) {
            Log::error('Coinbase Payment Processing Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('trial.payment.options')->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán của bạn. Vui lòng thử lại.');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        Log::info('Coinbase payment cancelled by user');
        return redirect()->route('trial.payment.options')->with('error', 'Bạn đã hủy thanh toán.');
    }
} 