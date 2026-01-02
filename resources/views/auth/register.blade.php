<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text" name="name" required placeholder="Full Name" value="{{ old('name') }}">
    <input type="email" name="email" required placeholder="Email" value="{{ old('email') }}">
    <input type="password" name="password" placeholder="Password (leave empty to auto-generate)">
    <input type="password" name="password_confirmation" placeholder="Confirm Password">
    
    <!-- hCaptcha -->
    <div class="h-captcha" data-sitekey="{{ env('HCAPTCHA_SITE_KEY') }}"></div>
    @if($errors->has('captcha'))
        <p style="color: red; margin-top: 8px;">{{ $errors->first('captcha') }}</p>
    @endif

    <button type="submit">Sign Up</button>
    </form>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
</x-guest-layout>
