<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate hCaptcha FIRST - chặn bot spam
        $captchaValid = $this->validateCaptcha($request->input('h-captcha-response'));
        
        if (!$captchaValid) {
            return back()
                ->withInput()
                ->withErrors(['captcha' => 'Vui lòng xác thực hCaptcha để tiếp tục.']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Validate hCaptcha response
     */
    private function validateCaptcha($captchaResponse): bool
    {
        if (!$captchaResponse) {
            Log::warning('hCaptcha: No response provided', ['ip' => request()->ip()]);
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => env('HCAPTCHA_SECRET'),
                'response' => $captchaResponse,
                'remoteip' => request()->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('hCaptcha validation for register', [
                'success' => $result['success'] ?? false,
                'ip' => request()->ip(),
                'error_codes' => $result['error-codes'] ?? []
            ]);
            
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('hCaptcha validation error', [
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);
            
            // Nếu hCaptcha service bị lỗi, block để an toàn
            return false;
        }
    }
}
