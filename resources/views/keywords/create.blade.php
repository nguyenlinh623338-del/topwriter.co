@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card with Shadow & Gradient -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-300">
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
                <div class="absolute inset-0 bg-[url('/img/pattern.svg')] opacity-10"></div>
                <div class="relative px-8 py-10 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Add New Keywords</h1>
                    <p class="text-lg text-indigo-100 max-w-3xl mx-auto">
                        Each keyword corresponds to 1 credit in your account
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

            <!-- Credit Information -->
            <div class="p-8 bg-white">
                <div class="flex items-center mb-6 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                    <div class="p-3 rounded-full bg-indigo-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700">Available Credits:</p>
                        <p class="text-xl font-bold text-indigo-700">{{ $availableCredits }} credits</p>
                    </div>
                </div>

                <form action="{{ route('keywords.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="keywords_field" class="block text-gray-700 font-medium mb-3 pl-1 text-lg">Enter new keywords</label>
                        <textarea name="keywords" id="keywords_field" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow min-h-[150px]" rows="5" placeholder="Enter your keywords here, separate them with commas, semicolons, or new lines">{{ old('keywords') }}</textarea>
                        
                        <div class="mt-3">
                            <div class="flex items-center">
                                <span id="keywordCount" class="text-sm text-indigo-600 font-medium">0 keywords</span>
                                <span class="mx-2 text-gray-400">|</span>
                                <span class="text-sm text-gray-600">1 credit = 1 keyword</span>
                            </div>
                            <p class="text-sm text-indigo-500 mt-1 ml-1">Tip: You can separate keywords with commas, semicolons, or new lines</p>
                        </div>
                        
                        @error('keywords')
                            <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100 mt-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="ml-3 text-sm text-blue-700">Each keyword will use 1 credit from your account. Keywords will be added to your dashboard immediately.</p>
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit" id="submitButton" class="w-full md:w-2/3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Add New Keywords
                        </button>
                        <p class="text-center text-sm text-gray-500 mt-3">
                            You will be redirected to the dashboard after successful submission
                        </p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- User Guide -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Credits Usage Guide</h2>
            
            <div class="prose max-w-none">
                <h3 class="text-lg font-semibold text-indigo-700">Each Credit allows you to:</h3>
                <ul class="space-y-2 text-gray-700 list-disc pl-5">
                    <li>Add 1 keyword to your dashboard</li>
                    <li>Get 1 professional SEO article (1000+ words) for that keyword</li>
                    <li>Receive detailed competitive analysis for the keyword</li>
                    <li>Optimize content according to SEO metrics</li>
                </ul>
                
                <h3 class="text-lg font-semibold text-indigo-700 mt-6">How to get more Credits?</h3>
                <p class="text-gray-700">You can purchase more Credits through our payment system. Each package of 30 Credits costs $600.</p>
                
                <div class="mt-6">
                    <a href="{{ route('credits.checkout') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buy More Credits
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const keywordsField = document.getElementById('keywords_field');
        const keywordCount = document.getElementById('keywordCount');
        const submitButton = document.getElementById('submitButton');
        const availableCredits = {{ $availableCredits }};
        
        function updateKeywordCount() {
            const keywordsString = keywordsField.value;
            let keywords = [];
            
            if (keywordsString.trim() !== '') {
                // Process keywords string into array
                const processedString = keywordsString.replace(/[\r\n;]/g, ',');
                keywords = processedString.split(',')
                    .map(keyword => keyword.trim())
                    .filter(keyword => keyword !== '');
            }
            
            const count = keywords.length;
            keywordCount.textContent = count + ' keywords';
            
            // Disable button if no keywords or not enough credits
            if (count === 0 || count > availableCredits) {
                submitButton.disabled = true;
                
                if (count > availableCredits) {
                    keywordCount.classList.add('text-red-600');
                    keywordCount.textContent = count + ' keywords (exceeds available credits)';
                } else {
                    keywordCount.classList.remove('text-red-600');
                }
            } else {
                submitButton.disabled = false;
                keywordCount.classList.remove('text-red-600');
            }
        }
        
        // Add listeners for input events
        keywordsField.addEventListener('input', updateKeywordCount);
        keywordsField.addEventListener('change', updateKeywordCount);
        keywordsField.addEventListener('keyup', updateKeywordCount);
        
        // Initialize count
        updateKeywordCount();
    });
</script>
@endsection 

@section('content')
<div class="py-12 bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card with Shadow & Gradient -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-300">
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
                <div class="absolute inset-0 bg-[url('/img/pattern.svg')] opacity-10"></div>
                <div class="relative px-8 py-10 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Add New Keywords</h1>
                    <p class="text-lg text-indigo-100 max-w-3xl mx-auto">
                        Each keyword corresponds to 1 credit in your account
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

            <!-- Credit Information -->
            <div class="p-8 bg-white">
                <div class="flex items-center mb-6 p-4 rounded-xl bg-indigo-50 border border-indigo-100">
                    <div class="p-3 rounded-full bg-indigo-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700">Available Credits:</p>
                        <p class="text-xl font-bold text-indigo-700">{{ $availableCredits }} credits</p>
                    </div>
                </div>

                <form action="{{ route('keywords.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label for="keywords_field" class="block text-gray-700 font-medium mb-3 pl-1 text-lg">Enter new keywords</label>
                        <textarea name="keywords" id="keywords_field" class="w-full border border-gray-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm hover:shadow-md transition-shadow min-h-[150px]" rows="5" placeholder="Enter your keywords here, separate them with commas, semicolons, or new lines">{{ old('keywords') }}</textarea>
                        
                        <div class="mt-3">
                            <div class="flex items-center">
                                <span id="keywordCount" class="text-sm text-indigo-600 font-medium">0 keywords</span>
                                <span class="mx-2 text-gray-400">|</span>
                                <span class="text-sm text-gray-600">1 credit = 1 keyword</span>
                            </div>
                            <p class="text-sm text-indigo-500 mt-1 ml-1">Tip: You can separate keywords with commas, semicolons, or new lines</p>
                        </div>
                        
                        @error('keywords')
                            <p class="text-red-500 text-sm mt-2 pl-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100 mt-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="ml-3 text-sm text-blue-700">Each keyword will use 1 credit from your account. Keywords will be added to your dashboard immediately.</p>
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit" id="submitButton" class="w-full md:w-2/3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Add New Keywords
                        </button>
                        <p class="text-center text-sm text-gray-500 mt-3">
                            You will be redirected to the dashboard after successful submission
                        </p>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- User Guide -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Credits Usage Guide</h2>
            
            <div class="prose max-w-none">
                <h3 class="text-lg font-semibold text-indigo-700">Each Credit allows you to:</h3>
                <ul class="space-y-2 text-gray-700 list-disc pl-5">
                    <li>Add 1 keyword to your dashboard</li>
                    <li>Get 1 professional SEO article (1000+ words) for that keyword</li>
                    <li>Receive detailed competitive analysis for the keyword</li>
                    <li>Optimize content according to SEO metrics</li>
                </ul>
                
                <h3 class="text-lg font-semibold text-indigo-700 mt-6">How to get more Credits?</h3>
                <p class="text-gray-700">You can purchase more Credits through our payment system. Each package of 30 Credits costs $600.</p>
                
                <div class="mt-6">
                    <a href="{{ route('credits.checkout') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buy More Credits
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const keywordsField = document.getElementById('keywords_field');
        const keywordCount = document.getElementById('keywordCount');
        const submitButton = document.getElementById('submitButton');
        const availableCredits = {{ $availableCredits }};
        
        function updateKeywordCount() {
            const keywordsString = keywordsField.value;
            let keywords = [];
            
            if (keywordsString.trim() !== '') {
                // Process keywords string into array
                const processedString = keywordsString.replace(/[\r\n;]/g, ',');
                keywords = processedString.split(',')
                    .map(keyword => keyword.trim())
                    .filter(keyword => keyword !== '');
            }
            
            const count = keywords.length;
            keywordCount.textContent = count + ' keywords';
            
            // Disable button if no keywords or not enough credits
            if (count === 0 || count > availableCredits) {
                submitButton.disabled = true;
                
                if (count > availableCredits) {
                    keywordCount.classList.add('text-red-600');
                    keywordCount.textContent = count + ' keywords (exceeds available credits)';
                } else {
                    keywordCount.classList.remove('text-red-600');
                }
            } else {
                submitButton.disabled = false;
                keywordCount.classList.remove('text-red-600');
            }
        }
        
        // Add listeners for input events
        keywordsField.addEventListener('input', updateKeywordCount);
        keywordsField.addEventListener('change', updateKeywordCount);
        keywordsField.addEventListener('keyup', updateKeywordCount);
        
        // Initialize count
        updateKeywordCount();
    });
</script>
@endsection 