@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg text-center">
    <h2 class="text-3xl font-bold text-indigo-600">🎉 Registration and Payment Successful!</h2>

    @if (session()->has('validatedData') || session()->has('success'))
        @php
            // If validatedData has been saved as an array, no need to json_decode
            $validatedData = session('validatedData');
        @endphp

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-md my-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <p class="text-gray-700 mt-4">
            Thank you, <strong>{{ $validatedData['name'] ?? 'User' }}</strong>. Your article request has been completed and verified.
        </p>

        <div class="mt-6 text-left">
            <h3 class="text-xl font-semibold">Article Details</h3>
            <ul class="list-disc ml-6 text-gray-600">
                <li><strong>Industry:</strong> 
                    @if(($validatedData['industry_type'] ?? '') == 'finance')
                        Finance - Investment - Crypto
                    @elseif(($validatedData['industry_type'] ?? '') == 'health')
                        Health - Medical - Aesthetics
                    @else
                        {{ $validatedData['industry_type'] ?? 'N/A' }}
                    @endif
                </li>
                <li><strong>Specific Field:</strong> {{ $validatedData['industry'] ?? 'N/A' }}</li>
                <li><strong>Keywords:</strong> 
                    @if(($validatedData['keyword_option'] ?? '') == 'provided')
                        {{ $validatedData['keywords'] ?? 'N/A' }}
                    @else
                        <span class="text-amber-600">Ahrefs analysis in progress. We'll create a 6-8 month content strategy based on your primary keywords.</span>
                        @if(!empty($validatedData['primary_keywords']))
                            <br><em>Primary Keywords: {{ $validatedData['primary_keywords'] }}</em>
                        @endif
                    @endif
                </li>
                <li><strong>Competitor Websites:</strong> 
                    @if(!empty($validatedData['competitor_links']) && is_array($validatedData['competitor_links']))
                        <ul class="list-disc ml-6 text-gray-600">
                            @foreach($validatedData['competitor_links'] as $link)
                                @if(!empty($link))
                                    <li>{{ $link }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        None provided
                    @endif
                </li>
                <li><strong>Special Requirements:</strong> {{ $validatedData['notes'] ?? 'None' }}</li>
                <li><strong>Direct Posting:</strong> {{ isset($validatedData['direct_posting']) ? 'Yes' : 'No' }}</li>
                @if(isset($validatedData['direct_posting']) && isset($validatedData['website_url']))
                    <li><strong>Website:</strong> {{ $validatedData['website_url'] }}</li>
                @endif
            </ul>
        </div>

        <div class="mt-8 bg-blue-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-3">Next Steps</h3>
            <p class="text-gray-700 mb-4">
                Your registration is now complete and your article request is being processed. 
                Our content team will begin working on your article soon.
            </p>

            <p class="text-gray-700 mb-4">
                You can view the status of your article on your dashboard at any time.
            </p>
        </div>

        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                Go to Dashboard
            </a>
        </div>
    @else
        <p class="text-red-500 text-lg mt-6">❌ Error: No data found. Please try again.</p>
        <a href="{{ route('try-writing') }}" class="text-indigo-600 font-semibold mt-4">Go Back</a>
    @endif
</div>
@endsection