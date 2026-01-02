@extends('layouts.app')

@section('content')
<!-- Nếu layout chưa load FontAwesome, bạn có thể thêm dòng dưới đây trong <head> của layouts/app.blade.php -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

<div class="bg-gradient-to-br from-indigo-100 to-blue-50 min-h-screen py-12">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-12 py-10 text-white">
                <h2 class="text-3xl font-bold text-center">Complete Your Registration</h2>
                <p class="mt-4 text-indigo-100 text-center">Your article is just moments away from being created.</p>
                <div class="w-24 h-1 bg-indigo-300 mx-auto mt-6 rounded-full"></div>
            </div>
        
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-md mx-8 my-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-6 py-4 rounded-md mx-8 my-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="p-8">
                @if(session()->has('validatedData'))
                    @php
                        $validatedData = session('validatedData');
                    @endphp

                    <div class="mb-8 text-left bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-xl shadow-md">
                        <p class="text-gray-700 mb-4 text-lg">
                            Thank you, <strong class="text-indigo-700">{{ $validatedData['name'] ?? 'User' }}</strong>! Your article request has been received.
                        </p>
                        
                        <p class="text-gray-700 mb-6">
                            To verify your request and continue the process, a small transaction fee of $1.00 will be charged and automatically refunded after verification.
                        </p>
                        
                        <h3 class="text-xl font-semibold mb-4 text-indigo-700 flex items-center">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 text-sm">i</div>
                            Your Article Details
                        </h3>
                        <ul class="list-disc ml-8 text-gray-600 mb-6">
                            <li class="mb-2"><strong>Industry:</strong> 
                                @if(($validatedData['industry_type'] ?? '') == 'finance')
                                    Finance - Investment - Crypto
                                @elseif(($validatedData['industry_type'] ?? '') == 'health')
                                    Health - Medical - Aesthetics
                                @else
                                    {{ $validatedData['industry_type'] ?? 'N/A' }}
                                @endif
                            </li>
                            <li><strong>Specific Field:</strong> {{ $validatedData['industry'] ?? 'N/A' }}</li>
                        </ul>
                    </div>
                @endif

                <h3 class="text-xl font-bold text-indigo-800 mb-6 text-center">Select Payment Method</h3>

                <div class="flex flex-col md:flex-row justify-center items-stretch space-y-6 md:space-y-0 md:space-x-6">
                    <!-- PayPal Button -->
                    <a href="{{ route('paypal.checkout') }}" class="payment-btn payment-btn-paypal">
                        <svg class="w-12 h-12 mb-4" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 0 0-.794.68l-.04.22-.63 4.876-.03.16a.81.81 0 0 1-.794.68h-2.52c-.447 0-.794-.38-.725-.83l.028-.18.48-3.89.03-.17c.07-.45.416-.83.863-.83h.724c2.556 0 4.664-1.032 5.214-4.217l.197-1.076c.078-.45.134-.866.17-1.252-.057-.187-.12-.373-.193-.534a3.55 3.55 0 0 0-.5-.797c-.148-.182-.322-.356-.514-.51zm-6.786 1.042c.048-.32.098-.95.157-.84.36.6.723.113 1.09.12.515.01 1.026-.057 1.526-.2.083-.23.166-.47.247-.072A5.456 5.456 0 0 1 18 7.865a4.456 4.456 0 0 0-4.256-.308c-1.245.572-2.17 1.505-2.676 2.835l-.394 1.028c-.032.078-.06.157-.084.237.591-.144 1.22-.218 1.853-.222l.838-.014zM8.684 7.18c.11-.52.373-.29.698-.29h4.6c.15 0 .294.014.431.042a3.84 3.84 0 0 1 1.107.287c.24.11.464.241.683.385a4.6 4.6 0 0 1 .636.504c.034-.236.034-.465 0-.686a3.7 3.7 0 0 0-.367-1.055 3.588 3.588 0 0 0-1.355-1.302c-.77-.446-1.655-.676-2.582-.676H8.684c-.448 0-.806.386-.852.83L6.098 15.071a.758.758 0 0 0 .752.847h2.296l.577-4.305 1.01-4.431z"></path>
                        </svg>
                        <span class="text-xl font-semibold mb-2">Pay with PayPal</span>
                                                        <span class="text-blue-100 text-sm">$1.00 transaction fee</span>
                    </a>

                    <!-- Crypto Button -->
                    <a href="{{ route('crypto.checkout') }}" class="payment-btn payment-btn-crypto">
                        <svg class="w-12 h-12 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H7"></path>
                        </svg>
                        <span class="text-xl font-semibold mb-2">Pay with Crypto</span>
                                                        <span class="text-green-100 text-sm">$1.00 transaction fee</span>
                    </a>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                        <p class="text-center text-sm text-gray-600">
                            Your payment information is securely processed and protected.
                        </p>
                        <p class="text-center text-xs text-gray-500 mt-2">
                            The transaction fee will be refunded after your account verification is complete.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection