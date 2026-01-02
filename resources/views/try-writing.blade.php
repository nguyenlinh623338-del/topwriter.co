@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header Card with Shadow & Gradient -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                <!-- Header -->
                <div class="relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
                    <div class="absolute inset-0 bg-[url('/img/pattern.svg')] opacity-10"></div>
                    <div class="relative px-8 py-14 text-center">
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Try Our Writing Service</h1>
                        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                            Get a custom sample article to see how our expert writers can boost your website's rankings.
                        </p>
                        <div class="w-28 h-1.5 bg-indigo-300 mx-auto mt-6 rounded-full"></div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mx-8 my-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-5 rounded-lg shadow-sm" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Simple Progress Indicator (Hidden initially, shown from step 2) -->
                <div id="progress-container" class="hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 px-6 py-3 mx-8 mb-4 rounded-r-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800" id="progress-text">
                                    Great! You're almost done — just a few more details to create your perfect sample article.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('process.try.writing') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" class="p-8 md:p-10" id="multi-step-form">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    
                    <!-- STEP 1: Basic Information (No progress bar) -->
                    <div id="step-1" class="step-container">
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-md p-8 border border-indigo-100/60 hover:border-indigo-200 transition-all duration-500">
                            <div class="text-center mb-8">
                                <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent mb-3">
                                    Get Your Free Sample Article
                                </h2>
                                <p class="text-gray-600 text-lg">Start with just a few details — it takes less than 30 seconds</p>
                        </div>
                        
                            <div class="space-y-6 max-w-2xl mx-auto">
                            <div class="group">
                                    <label class="block text-gray-700 font-medium mb-3 pl-1 group-hover:text-indigo-700 transition-colors">Your Name</label>
                                    <input type="text" name="name" id="name" class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow text-lg" value="{{ old('name') }}" required placeholder="John Smith">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                    <label class="block text-gray-700 font-medium mb-3 pl-1 group-hover:text-indigo-700 transition-colors">Email Address</label>
                                    <input type="email" name="email" id="email" class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow text-lg" value="{{ old('email') }}" required placeholder="john@yourcompany.com">
                                    <p class="text-sm text-gray-500 mt-2 pl-1">We'll send your sample article and account details here</p>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                @enderror
                                </div>

                                <div class="group">
                                    <label class="block text-gray-700 font-medium mb-3 pl-1 group-hover:text-indigo-700 transition-colors">Your Website URL</label>
                                    <input type="text" name="website_url" id="website_url" class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow text-lg" value="{{ old('website_url') }}" placeholder="https://yourcompany.com">
                                    <p class="text-sm text-gray-500 mt-2 pl-1">This helps us create content that matches your brand and industry</p>
                                    @error('website_url')
                                        <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-8">
                                <button type="button" id="next-to-step-2" class="w-full md:w-auto px-12 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-lg font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    Continue →
                                </button>
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Your information is secure and never shared
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Detailed Requirements -->
                    <div id="step-2" class="step-container hidden">
                        <!-- Qualification Message -->
                        <div class="mb-8 p-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Premium Content Strategy</h3>
                                    <p class="text-amber-700">Due to high demand, we can only accept a limited number of demo requests each week. To ensure we provide the most relevant sample, we prioritize businesses looking for premium content solutions ($500+/month). Please provide detailed information so our expert writers can create the best demo for your needs.</p>
                                    
                                    <!-- Optional: Not Ready Button -->
                                    <div class="mt-4">
                                        <button type="button" id="not-ready-btn" class="text-sm text-amber-600 hover:text-amber-800 underline transition-colors">
                                            Not ready for premium ($500+/month)? Click here
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Requirements -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-md p-8 border border-blue-100/60 hover:border-blue-200 transition-all duration-500">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 bg-gradient-to-br from-indigo-600 to-purple-600 w-10 h-10 rounded-full flex items-center justify-center shadow-md">
                                <span class="text-white font-bold">2</span>
                            </div>
                            <h2 class="ml-4 text-2xl font-bold bg-gradient-to-r from-indigo-700 to-purple-700 bg-clip-text text-transparent">
                                    Content Requirements
                            </h2>
                        </div>
                        
                            <div class="space-y-8">
                            <!-- Website Industry -->
                            <div>
                                    <label class="block text-gray-700 font-medium mb-4 pl-1 text-lg">Website Industry</label>
                                    <div class="grid grid-cols-1 gap-4">
                                    <div class="relative">
                                         <label class="flex items-start p-6 rounded-xl cursor-pointer group bg-white border border-indigo-300 shadow-sm hover:shadow-md transition-all duration-300 bg-indigo-50">
                                             <input type="radio" name="industry_type" value="health" class="w-5 h-5 text-indigo-600 mt-1 flex-shrink-0" required checked>
                                            <div class="ml-4">
                                                 <p class="font-bold text-indigo-800 group-hover:text-indigo-700 transition-colors">Health - Medical - Aesthetics</p>
                                                 <p class="text-sm text-indigo-700 mt-2">Healthcare, medical services, aesthetics, and beauty industry</p>
                                            </div>
                                             <div class="absolute h-full w-1 bg-indigo-600 left-0 top-0 rounded-l-xl"></div>
                                        </label>
                                    </div>
                                </div>
                                
                                    <div class="mt-4">
                                    <input type="text" name="industry" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow" value="{{ old('industry') }}" placeholder="Specify your industry (e.g. Spa, Medical Clinic, Dental Practice, Aesthetic Surgery, etc.)" required>
                                        <p class="text-sm text-gray-500 mt-2 pl-1">
                                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            We ask about your industry, keywords, and competitors to ensure your sample matches your goals and market.
                                        </p>
                                    @error('industry')
                                        <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keywords -->
                            <div>
                                    <label class="block text-gray-700 font-medium mb-4 pl-1 text-lg">Keywords</label>
                                    <div class="mb-4 p-6 rounded-xl bg-white border border-indigo-100 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all duration-300">
                                        <div class="flex items-center mb-4 group cursor-pointer">
                                        <input type="radio" id="keywords_provided" name="keyword_option" value="provided" checked class="w-5 h-5 text-indigo-600 flex-shrink-0">
                                        <label for="keywords_provided" class="text-gray-700 font-medium ml-4 cursor-pointer group-hover:text-indigo-700 transition-colors">I'll provide keywords</label>
                                    </div>
                                    <div class="flex items-center group cursor-pointer">
                                        <input type="radio" id="keywords_analysis" name="keyword_option" value="analysis" class="w-5 h-5 text-indigo-600 flex-shrink-0">
                                        <label for="keywords_analysis" class="text-gray-700 font-medium ml-4 cursor-pointer group-hover:text-indigo-700 transition-colors">Analyze keywords with Ahrefs and filter for your website, creating a 6-8 month content strategy</label>
                                    </div>
                                </div>
                                <textarea name="keywords" id="keywords_field" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow" rows="4" placeholder="Enter keywords separated by commas, semicolons, or line breaks">{{ old('keywords') }}</textarea>
                                    <p class="text-sm text-indigo-500 mt-2 ml-1">Tip: You can separate keywords using commas, semicolons, or by pressing Enter</p>
                                
                                    <div id="primary_keywords_section" class="mt-6 hidden">
                                    <div class="p-6 rounded-xl bg-white border border-indigo-100 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all duration-300">
                                        <label class="block text-gray-700 font-medium mb-3">Suggest your primary target keywords</label>
                                        <p class="text-sm text-gray-500 mb-4">Enter the most important keywords for your business goals</p>
                                        <textarea name="primary_keywords" id="primary_keywords_field" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" rows="4" placeholder="Enter your most challenging target keywords that align with your business goals">{{ old('primary_keywords') }}</textarea>
                                    </div>
                                </div>
                                
                                @error('keywords')
                                    <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Competitor Websites -->
                            <div>
                                    <label class="block text-gray-700 font-medium mb-4 pl-1 text-lg">Competitor Websites</label>
                                <div id="competitor-inputs" class="space-y-4">
                                    <div class="competitor-input">
                                        <div class="flex items-center">
                                            <input type="text" name="competitor_links[]" class="flex-grow border border-gray-200 rounded-l-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" value="{{ old('competitor_links.0') }}" placeholder="E.g. https://competitor.com">
                                            <button type="button" class="add-competitor px-6 h-[54px] bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-r-xl transition-all duration-300 flex items-center justify-center shadow">
                                                <span class="mr-2">Add</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-3 pl-1">Used for researching effective content & keywords</p>
                                @error('competitor_links')
                                    <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Special Requirements -->
                            <div>
                                    <label class="block text-gray-700 font-medium mb-4 pl-1 text-lg">5 Key Strengths of Your Website/Business vs Market (Optional)</label>
                                
                                <!-- Toggle para hiện/ẩn gợi ý -->
                                <div class="mb-4 flex items-center">
                                    <label for="show_suggestions" class="flex items-center cursor-pointer">
                                        <span class="mr-3 text-gray-600 text-sm">Need help filling this out?</span>
                                        <div class="relative inline-flex items-center">
                                            <input type="checkbox" id="show_suggestions" class="sr-only">
                                            <div class="w-10 h-5 bg-gray-200 rounded-full shadow-inner"></div>
                                            <div class="dot absolute w-4 h-4 bg-white rounded-full shadow top-0.5 left-0.5 transition"></div>
                                        </div>
                                        <span class="ml-2 text-gray-600 text-sm">Show suggestions</span>
                                    </label>
                                </div>
                                
                                <!-- Phần gợi ý sẽ hiện khi click toggle -->
                                <div id="suggestions_container" class="mb-6 p-5 rounded-xl bg-indigo-50 border border-indigo-200 hidden">
                                    <h4 class="font-semibold text-indigo-700 mb-3">Suggestions to help clarify your requirements:</h4>
                                        <div class="space-y-3 text-gray-700 text-sm">
                                            <div><strong>Target Market:</strong> Where is your target market?</div>
                                            <div><strong>Target Audience:</strong> Who are your ideal customers? (specific persona)</div>
                                            <div><strong>Problem Solved:</strong> What problem does your product/service solve for them?</div>
                                            <div><strong>Unique Selling Point:</strong> What makes you different or better than competitors?</div>
                                            <div><strong>Customer Feedback:</strong> What do customers usually praise you for?</div>
                                            <div><strong>Price Positioning:</strong> Are you premium, mid-range, or low-cost?</div>
                                            <div><strong>Brand Image:</strong> How do you want customers to remember your brand?</div>
                                            <div><strong>Avoided Terms/Imagery:</strong> Are there any words or visuals that should not appear?</div>
                                            <div><strong>Brand Guidelines:</strong> Do you have a brandbook or style guide to follow?</div>
                                    </div>
                                </div>
                                
                                <textarea name="notes" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow" rows="5" placeholder="E.g. unique technology, superior customer service, exclusive partnerships, industry expertise, cost advantages, etc.">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                                @enderror
                            </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for backend compatibility -->
                        <input type="hidden" name="direct_posting" value="0">
                        <input type="hidden" name="login_url" value="">
                        <input type="hidden" name="website_username" value="">
                        <input type="hidden" name="website_password" value="">

                        <!-- hCaptcha Protection -->
                        <div class="mt-8 flex justify-center">
                            <div class="h-captcha" data-sitekey="{{ env('HCAPTCHA_SITE_KEY') }}"></div>
                        </div>
                        @if($errors->has('captcha'))
                            <p class="text-red-500 text-sm text-center mt-2">{{ $errors->first('captcha') }}</p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <button type="button" id="back-to-step-1" class="order-2 sm:order-1 w-full sm:w-auto px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:border-indigo-500 hover:text-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-medium transition-all duration-300">
                                ← Back
                            </button>
                            
                            <div class="order-1 sm:order-2 text-center flex-1 max-w-md">
                                <button type="submit" id="submit-form" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    Get My Free Sample Article
                                </button>
                                
                                <!-- Privacy Message -->
                                <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-center">
                                        <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        <p class="text-sm text-green-700 text-center">
                                            <strong>No login or admin info required.</strong> Your privacy and website security are our top priority. All information is confidential and never shared.
                                        </p>
                                    </div>
                                </div>
                                
                                <p class="text-center text-sm text-gray-500 mt-3">
                                    You will receive a confirmation email after submission
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Footer -->
                <div class="px-8 pb-8">
                    <div class="border-t border-gray-200 pt-6">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-xl shadow-sm">
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="ml-2 text-center text-sm text-gray-600">
                                    Your information is securely processed and protected
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thank You Modal for Not Ready Users -->
<div id="not-ready-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Thank You for Your Interest!</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    We appreciate your interest! We'll keep your information on file and may reach out with special offers for businesses ready to grow their content marketing.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="close-modal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const progressContainer = document.getElementById('progress-container');
    const progressText = document.getElementById('progress-text');
    const nextBtn = document.getElementById('next-to-step-2');
    const backBtn = document.getElementById('back-to-step-1');
    const notReadyBtn = document.getElementById('not-ready-btn');
    const notReadyModal = document.getElementById('not-ready-modal');
    const closeModalBtn = document.getElementById('close-modal');
    
    let currentStep = 1;
    
    // Step 1 validation
    function validateStep1() {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!name || !email) {
            alert('Please fill in your name and email to continue.');
            return false;
        }
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return false;
        }
        
        return true;
    }
    
    // Step navigation
    nextBtn.addEventListener('click', function() {
        if (validateStep1()) {
            // Track step 1 completion
            trackEvent('step_1_completed', {
                'event_category': 'form',
                'event_label': 'step_1_to_step_2'
            });
            
            currentStep = 2;
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            progressContainer.classList.remove('hidden');
            
            // Update progress indicator
            setTimeout(() => {
                updateProgressText("Great! You're almost done — just a few more details to create your perfect sample article.");
            }, 100);
            
                         // Scroll to progress bar first, then to first field
             setTimeout(() => {
                 const progressContainer = document.getElementById('progress-container');
                 if (progressContainer) {
                     progressContainer.scrollIntoView({ 
                         behavior: 'smooth', 
                         block: 'start',
                         inline: 'nearest'
                     });
                     
                     // Then scroll to first field after progress bar animation
                     setTimeout(() => {
                         const firstField = document.querySelector('input[name="industry"]');
                         if (firstField) {
                             firstField.scrollIntoView({ 
                                 behavior: 'smooth', 
                                 block: 'center',
                                 inline: 'nearest'
                             });
                             // Focus on the field after scroll
                             setTimeout(() => {
                                 firstField.focus();
                             }, 300);
                         }
                     }, 800);
                 }
             }, 200);
        }
    });
    
    backBtn.addEventListener('click', function() {
        currentStep = 1;
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        progressContainer.classList.add('hidden');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Simple progress text update
    function updateProgressText(text) {
        progressText.textContent = text;
    }
    
    // Handle not ready button
    notReadyBtn.addEventListener('click', function() {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const website = document.getElementById('website_url').value.trim();
        
        if (!name || !email) {
            alert('Please complete Step 1 first (name and email are required).');
            return;
        }
        
        // Track nurture lead
        trackEvent('nurture_lead', {
            'event_category': 'lead_qualification',
            'event_label': 'not_ready_for_premium'
        });
        
        // Send data to server for nurture flow
        fetch('/api/nurture-lead', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                name: name,
                email: email,
                website_url: website,
                reason: 'not_ready_for_premium_500_per_month'
            })
        }).then(response => response.json())
          .then(data => {
              console.log('Nurture lead saved:', data);
          })
          .catch(error => {
              console.error('Error saving nurture lead:', error);
          });
        
        // Show thank you modal
        notReadyModal.classList.remove('hidden');
    });
    
    // Close modal
    closeModalBtn.addEventListener('click', function() {
        notReadyModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    notReadyModal.addEventListener('click', function(e) {
        if (e.target === notReadyModal) {
            notReadyModal.classList.add('hidden');
        }
    });
    
    // Form submission tracking
    document.getElementById('submit-form').addEventListener('click', function() {
        // Track form submission
        trackEvent('form_submitted', {
            'event_category': 'form',
            'event_label': 'trial_writing_form'
        });
    });
    
    // Track step 1 start (page load)
    trackEvent('step_1_started', {
        'event_category': 'form',
        'event_label': 'form_page_loaded'
    });
    
    // Track step 2 reached
    function trackStep2Reached() {
        trackEvent('step_2_reached', {
            'event_category': 'form',
            'event_label': 'qualification_step'
        });
    }
    
    // Tracking function (works with GA4 and LinkedIn)
    function trackEvent(eventName, parameters) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, parameters);
        }
        
        // LinkedIn Insight Tag
        if (typeof lintrk !== 'undefined') {
            lintrk('track', { conversion_id: eventName });
        }
        
        // Console log for debugging
        console.log('Event tracked:', eventName, parameters);
    }
        
        // Keyword options toggle
        const keywordsField = document.getElementById('keywords_field');
        const keywordsProvided = document.getElementById('keywords_provided');
        const keywordsAnalysis = document.getElementById('keywords_analysis');
        const primaryKeywordsSection = document.getElementById('primary_keywords_section');
        
        function updateKeywordFields() {
            if (keywordsProvided.checked) {
                keywordsField.disabled = false;
                keywordsField.placeholder = "Enter keywords separated by commas, semicolons, or line breaks";
                primaryKeywordsSection.classList.add('hidden');
            } else {
                keywordsField.disabled = true;
                keywordsField.placeholder = "Keywords will be analyzed with Ahrefs and provided within 7 days";
                primaryKeywordsSection.classList.remove('hidden');
            }
        }
        
        keywordsProvided.addEventListener('change', updateKeywordFields);
        keywordsAnalysis.addEventListener('change', updateKeywordFields);
        
        // Initialize state
        updateKeywordFields();
        
        // Add competitor functionality
        const competitorContainer = document.getElementById('competitor-inputs');
        
        function addCompetitorInput() {
            const newInput = document.createElement('div');
            newInput.className = 'competitor-input';
            newInput.innerHTML = `
                <div class="flex items-center mt-4">
                    <input type="text" name="competitor_links[]" class="flex-grow border border-gray-200 rounded-l-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" placeholder="E.g. https://competitor.com">
                    <button type="button" class="remove-competitor px-6 h-[54px] bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-r-xl transition-all duration-300 flex items-center justify-center shadow">
                        <span class="mr-2">Remove</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
            competitorContainer.appendChild(newInput);
            
            // Add event listener to new remove button
            const removeButton = newInput.querySelector('.remove-competitor');
            removeButton.addEventListener('click', function() {
                competitorContainer.removeChild(newInput);
            });
        }
        
        // Add event listener to initial add button
        document.querySelector('.add-competitor').addEventListener('click', addCompetitorInput);
        
        // Event delegation for dynamically added buttons
        competitorContainer.addEventListener('click', function(e) {
            if (e.target.closest('.add-competitor')) {
                addCompetitorInput();
            }
        });
        
        // Toggle suggestions
        const showSuggestionsCheckbox = document.getElementById('show_suggestions');
        const suggestionsContainer = document.getElementById('suggestions_container');
        
        showSuggestionsCheckbox.addEventListener('change', function() {
            suggestionsContainer.classList.toggle('hidden', !this.checked);
            
            // Animate dot
            const dot = this.parentElement.querySelector('.dot');
            if (this.checked) {
                dot.classList.add('transform');
                dot.style.transform = 'translateX(20px)';
                dot.classList.add('bg-indigo-600');
            } else {
                dot.classList.remove('transform');
                dot.style.transform = 'translateX(0)';
                dot.classList.remove('bg-indigo-600');
            }
        });
    });
</script>
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
@endsection