@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-teal-600 opacity-90"></div>
                <div class="absolute inset-0 bg-[url('/img/pattern.svg')] opacity-10"></div>
                <div class="relative px-8 py-14 text-center">
                    <div class="inline-flex items-center justify-center p-4 bg-white bg-opacity-20 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Payment Successful!</h1>
                    <p class="text-xl text-green-100 max-w-3xl mx-auto">
                        30 credits have been added to your account
                    </p>
                    <div class="w-28 h-1.5 bg-green-300 mx-auto mt-6 rounded-full"></div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="p-8 md:p-12">
                <!-- Success Message -->
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Thank You For Your Purchase</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        Your account has been credited with 30 credits, which you can use to generate 3 premium SEO-optimized articles. Your support helps us continue to improve our services.
                    </p>
                </div>

                <!-- Credit Balance -->
                <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-8 border border-green-100 shadow-md mb-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 font-bold text-sm mb-4">
                                YOUR ACCOUNT
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">{{ Auth::user()->credits }} Credits</h2>
                            <div class="mt-2 text-lg text-green-700 font-medium">
                                Available in your account now
                            </div>
                        </div>
                        
                        <a href="{{ route('dashboard') }}" class="btn btn-gradient-green btn-md mt-6 md:mt-0 transform hover:-translate-y-1">
                            Go to Dashboard
                        </a>
                    </div>
                </div>

                <!-- How to Use Credits -->
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">How to Use Your Credits</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-green-100 rounded-full text-green-600 font-bold text-lg">
                                    1
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Create a New Article</h4>
                                    <p class="mt-2 text-gray-600">Go to your dashboard and click the "New Article" button to start the article creation process.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-green-100 rounded-full text-green-600 font-bold text-lg">
                                    2
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Provide Article Details</h4>
                                    <p class="mt-2 text-gray-600">Enter your target keyword, website URL, and other requirements to help us create a tailored SEO article for your site.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-green-100 rounded-full text-green-600 font-bold text-lg">
                                    3
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">Receive Your Premium Article</h4>
                                    <p class="mt-2 text-gray-600">Our team will research your competitors and create a high-quality article optimized for your keywords. Article pricing ranges from 1 to 7.5 credits based on word count, complexity, research depth, and delivery speed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Benefits -->
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 p-6 rounded-xl border border-teal-100">
                    <h3 class="text-xl font-bold text-teal-800 mb-4">Benefits of Your Premium Articles</h3>
                    
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Improved search engine rankings with targeted keyword optimization</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Higher-quality backlinks from authoritative sources</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Increased organic traffic through strategic content positioning</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Better user engagement with high-value, well-researched content</span>
                        </li>
                    </ul>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-success btn-md inline-flex items-center">
                            Return to Dashboard
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 