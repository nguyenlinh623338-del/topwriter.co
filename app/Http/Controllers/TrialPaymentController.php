<?php
/* trialpayment cũ - đăng nhập và thanh toán luôn 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\PayPalService;
use App\Services\CoinbasePaymentService; // Service xử lý Coinbase

class TrialPaymentController extends Controller
{
    public function handle(Request $request)
    {
        // 🔹 Kiểm tra dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'title' => 'required|string|max:255',
            'audience' => 'required|string|max:255',
            'keywords' => 'required|string',
            'word_count' => 'required|integer',
            'tone' => 'required|string',
            'competitor_links' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // 🔹 Kiểm tra nếu user đã có tài khoản
        $user = User::where('email', $validatedData['email'])->first();
        if (!$user) {
            // Tạo mật khẩu ngẫu nhiên
            $password = str()->random(12);
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($password),
            ]);

            // Gửi email chứa mật khẩu
            Mail::raw("Your account has been created. Your password: $password", function ($message) use ($validatedData) {
                $message->to($validatedData['email'])
                        ->subject('Your New Account Details');
            });
        }

        // 🔹 Tự động đăng nhập user
        Auth::login($user);

        // 🔹 Tạo thanh toán 0 USD qua PayPal
        $paypalService = new PayPalService();
        $paypalPayment = $paypalService->createPayment(0);

        // 🔹 Tạo thanh toán 0 USD qua Coinbase Commerce
        $coinbaseService = new CoinbasePaymentService();
        $coinbasePayment = $coinbaseService->createPayment(0);

        // 🔹 Chuyển hướng đến trang xác nhận với 2 phương thức thanh toán
        return redirect()->route('trial.success')->with([
            'validatedData' => $validatedData,
            'paypalLink' => $paypalPayment['links'][1]['href'],
            'cryptoLink' => $coinbasePayment['hosted_url']
        ]);   
    }
}
Hết trialpayment cũ  */


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrialPaymentController extends Controller
{
    public function handle(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email',
            'title'            => 'required|string|max:255',
            'audience'         => 'required|string|max:255',
            'keywords'         => 'required|string',
            'word_count'       => 'required|integer',
            'tone'             => 'required|string',
            'competitor_links' => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        // Lưu validatedData vào session để dùng sau (trong callback thanh toán)
        session(['validatedData' => $validatedData]);

        // Redirect đến trang Payment Options (bạn sẽ tạo view này, ví dụ: trial.payment_options)
        return redirect()->route('trial.payment.options');
    }
}