<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrialWritingController;
use App\Http\Controllers\TrialPaymentController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoinbasePaymentController;
use App\Http\Controllers\TestApiController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\CoinbaseCreditsController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\DashboardSyncController;
use App\Http\Controllers\TicketController;

// Test API JSON Format
Route::get('/test-api-json', [TestApiController::class, 'testJsonFormat']);

// Nurture Lead API for multi-step form
Route::post('/api/nurture-lead', [TrialWritingController::class, 'nurtureLead'])->name('api.nurture.lead');

// Coinbase (Crypto) routes
Route::get('/crypto/checkout', [CoinbasePaymentController::class, 'checkout'])->name('crypto.checkout');
Route::get('/crypto/success', [CoinbasePaymentController::class, 'success'])->name('crypto.success');
Route::get('/crypto/cancel', [CoinbasePaymentController::class, 'cancel'])->name('crypto.cancel');

// PayPal routes for trial 
Route::get('/paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');
// Sử dụng đường dẫn cũ: /paypal-success
Route::get('/paypal-success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('/paypal-cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

// Credits routes
Route::middleware(['auth'])->group(function () {
    Route::get('/credits/buy', [CreditsController::class, 'checkout'])->name('credits.buy');
    Route::get('/credits/checkout', [CreditsController::class, 'checkout'])->name('credits.checkout');
    Route::get('/credits/confirm', [CreditsController::class, 'confirm'])->name('credits.confirm');
    
    // PayPal routes cho credits
    Route::get('/credits/paypal/checkout', [CreditsController::class, 'processPayPal'])->name('credits.paypal.process');
    Route::get('/credits/paypal/success', [CreditsController::class, 'paypalSuccess'])->name('credits.paypal.success');
    Route::get('/credits-paypal-success', [CreditsController::class, 'paypalSuccess'])->name('credits.paypal.success.alt'); // URL thay thế
    Route::get('/credits/paypal/cancel', [CreditsController::class, 'paypalCancel'])->name('credits.paypal.cancel');
    
    // Coinbase routes cho credits
    Route::get('/credits/crypto/checkout', [CoinbaseCreditsController::class, 'checkout'])->name('credits.crypto.process');
    Route::get('/credits/crypto/success', [CoinbaseCreditsController::class, 'success'])->name('credits.crypto.success');
    Route::get('/credits/crypto/cancel', [CoinbaseCreditsController::class, 'cancel'])->name('credits.crypto.cancel');
    
    Route::get('/credits/success', [CreditsController::class, 'success'])->name('credits.success');
    
    // Keywords routes
    Route::get('/keywords/create', [KeywordController::class, 'create'])->name('keywords.create');
    Route::post('/keywords/store', [KeywordController::class, 'store'])->name('keywords.store');

    // Revision routes
    Route::post('/revisions/store', [RevisionController::class, 'store'])->name('revisions.store');
});

// Dashboard route (cho Dashboard của user)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/sync', [DashboardSyncController::class, 'sync'])->name('dashboard.sync')->middleware('auth');
Route::post('/dashboard/sync', [DashboardSyncController::class, 'sync'])->name('dashboard.sync.post')->middleware('auth');

// Support Ticket System
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
});

// Google OAuth Login routes
Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

// Trang Try Writing: form brief và xử lý form
Route::get('/try-writing', [TrialWritingController::class, 'show'])->name('try-writing');
Route::post('/try-writing', [TrialWritingController::class, 'processTrialPayment'])->name('process.try.writing');

// Xử lý form Trial Payment: validate và lưu dữ liệu vào session,
// sau đó redirect sang Payment Options page
Route::post('/trial-payment', [TrialPaymentController::class, 'handle'])
    ->name('process.trial.payment')
    ->middleware('web');

// Payment Options: trang cho người dùng chọn phương thức thanh toán
Route::get('/trial-payment-options', function () {
    if (!session()->has('validatedData')) {
        return redirect()->route('try-writing')->with('error', 'No data found. Please try again.');
    }
    return view('trial.payment_options');
})->name('trial.payment.options');

// Trial Success: sau khi thanh toán thành công và callback xử lý user,
// hiển thị thông báo thành công
Route::get('/trial-success', function () {
    return view('trial.success');
})->name('trial.success');

// Trang Welcome / Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Liên hệ - tạm thời chỉ hiển thị form liên hệ
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Các route liên quan đến Profile (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';