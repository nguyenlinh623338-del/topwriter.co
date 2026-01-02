@extends('layouts.app')

@section('content')
<style>
    /* Fix button colors */
    .keywords-btn {
        background-color: #4F46E5 !important; /* Indigo-600, màu chính của Tailwind */
        color: white !important;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.25) !important;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease !important;
        border: none !important;
    }
    .keywords-btn:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }
    .keywords-btn:hover {
        background-color: #4338CA !important; /* Indigo-700 */
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 12px rgba(79, 70, 229, 0.35) !important;
    }
    .keywords-btn:hover:before {
        left: 100%;
    }
    .keywords-btn:active {
        transform: translateY(0) !important;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2) !important;
    }
    .submit-btn {
        background-color: rgb(22, 163, 74) !important;
        color: white !important;
    }
    .submit-btn:hover {
        background-color: rgb(21, 128, 61) !important;
    }

    /* Thêm một kích hoạt nhỏ để thu hút sự chú ý */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
        }
    }
    
    .keywords-btn {
        animation: pulse 2s infinite;
    }
    .keywords-btn:hover {
        animation: none;
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome, {{ Auth::user()->name ?? 'User' }}!</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('tickets.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        💬 Get Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm mb-6" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Credits Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
            <div class="flex flex-wrap items-start justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Available Credits</p>
                        <p class="text-2xl font-semibold">{{ auth()->user()->credits }}</p>
                        @if(!auth()->user()->trial_used)
                            <span class="text-green-600 text-sm">Free Trial Available</span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('credits.checkout') }}" class="inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Credits
                    </a>
                </div>
            </div>
            
            <!-- Credits Explanation -->
            <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100">
                <h4 class="font-medium text-indigo-800 mb-2">What can you do with credits?</h4>
                <div class="text-sm text-gray-700">
                    <p><strong>30 credits = 30 premium SEO-optimized articles</strong> (1000+ words each)</p>
                    <p class="mt-2">Each article includes:</p>
                    <ul class="list-disc pl-5 mt-1 space-y-1">
                        <li>In-depth competitor research from top-ranking websites</li>
                        <li>Search intent analysis to match exactly what users are looking for</li>
                        <li>Unique insights not found in competing articles</li>
                        <li>High-quality backlinks to boost your rankings</li>
                        <li>Pyramid content structure for pillar articles to maximize SEO impact</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Article Requests List -->
        <div class="space-y-6">
            <div class="flex justify-between items-center px-4">
                <h3 class="text-lg font-semibold text-gray-900">Article List</h3>
                @if(isset($dashboardSheet))
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-4">Last updated: {{ $dashboardSheet->last_synced_at ? $dashboardSheet->last_synced_at->diffForHumans() : 'Never' }}</span>
                    
                    <!-- Update Dashboard Button/Form -->
                    <div class="inline-flex items-center">
                        @if(session('show_captcha'))
                            <!-- Show captcha form -->
                            <form id="updateDashboardForm" method="POST" action="{{ route('dashboard.sync.post') }}" class="inline">
                                @csrf
                                <div class="flex items-center space-x-3">
                                    <div class="h-captcha" data-sitekey="{{ env('HCAPTCHA_SITE_KEY') }}"></div>
                                    <button type="submit" class="text-green-600 hover:text-green-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                                            Update Dashboard
                        </span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Regular update button -->
                            <a href="{{ route('dashboard.sync') }}" class="text-green-600 hover:text-green-800" id="updateDashboardBtn">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                                    Update Dashboard
                        </span>
                    </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @if(isset($dashboardItems) && count($dashboardItems) > 0)
                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Keyword
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Link Top
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Idea
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Google Docs
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Post
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dashboardItems as $item)
                                    <tr class="hover:bg-gray-50 {{ isset($item->revision_status) && $item->revision_status === '0' ? 'bg-yellow-50' : '' }}" 
                                        {{ isset($item->revision_status) && $item->revision_status === '0' ? 'title="Being revised - maximum 72 hours after receiving feedback"' : '' }}>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $item->keyword }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            @if($item->link_top)
                                                <div class="relative">
                                                    <div class="link-preview">{{ Str::limit($item->link_top, 50) }}</div>
                                                    @if(strlen($item->link_top) > 50)
                                                        <button 
                                                            class="text-xs text-blue-600 hover:text-blue-800 ml-1 focus:outline-none"
                                                            onclick="toggleLinkModal('{{ $loop->index }}')"
                                                        >
                                                            Show More
                                                        </button>
                                                        <!-- Modal -->
                                                        <div id="link-modal-{{ $loop->index }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                                                            <div class="bg-white rounded-lg shadow-xl max-w-4xl mx-auto p-6 max-h-[80vh] overflow-y-auto">
                                                                <div class="flex justify-between items-start mb-4">
                                                                    <h3 class="text-lg font-semibold text-gray-900">Top Links for "{{ $item->keyword }}"</h3>
                                                                    <button onclick="toggleLinkModal('{{ $loop->index }}')" class="text-gray-400 hover:text-gray-600">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-600 whitespace-pre-wrap">
                                                                    {!! nl2br(e($item->link_top)) !!}
                                                                </div>
                                                                <div class="mt-4 flex justify-end">
                                                                    <button onclick="toggleLinkModal('{{ $loop->index }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if($item->idea)
                                                <div class="relative">
                                                    <div class="idea-preview">{{ Str::limit($item->idea, 50) }}</div>
                                                    @if(strlen($item->idea) > 50)
                                                        <button 
                                                            class="text-xs text-blue-600 hover:text-blue-800 ml-1 focus:outline-none"
                                                            onclick="toggleIdeaModal('{{ $loop->index }}')"
                                                        >
                                                            Show More
                                                        </button>
                                                        <!-- Modal -->
                                                        <div id="idea-modal-{{ $loop->index }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                                                            <div class="bg-white rounded-lg shadow-xl max-w-4xl mx-auto p-6 max-h-[80vh] overflow-y-auto">
                                                                <div class="flex justify-between items-start mb-4">
                                                                    <h3 class="text-lg font-semibold text-gray-900">Full Idea Content</h3>
                                                                    <button onclick="toggleIdeaModal('{{ $loop->index }}')" class="text-gray-400 hover:text-gray-600">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="mt-2 text-sm text-gray-600 whitespace-pre-wrap">
                                                                    {!! nl2br(e($item->idea)) !!}
                                                                </div>
                                                                <div class="mt-4 flex justify-end">
                                                                    <button onclick="toggleIdeaModal('{{ $loop->index }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none">
                                                                        Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            @if($item->link_docs)
                                                <a href="{{ $item->link_docs }}" target="_blank" class="text-green-600 hover:text-green-800">
                                                    <span class="inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        View
                                                    </span>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            @if($item->link_post)
                                                <a href="{{ $item->link_post }}" target="_blank" class="text-purple-600 hover:text-purple-800">
                                                    <span class="inline-flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        View
                                                    </span>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @if(isset($item->revision_status))
                                                @if($item->revision_status === '0')
                                                    <div class="group relative">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 cursor-help">
                                                            In Revision
                                                        </span>
                                                        @if($item->revision)
                                                            <div class="absolute z-10 hidden group-hover:block bg-gray-900 text-white text-sm rounded p-2 shadow-lg w-64 -ml-24 mt-1">
                                                                <p class="font-bold mb-1">Revision request:</p>
                                                                <p>{{ $item->revision }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($item->revision_status === '1')
                                                    <div class="group relative">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 cursor-help">
                                                            Revised
                                                        </span>
                                                        @if($item->revision)
                                                            <div class="absolute z-10 hidden group-hover:block bg-gray-900 text-white text-sm rounded p-2 shadow-lg w-64 -ml-24 mt-1">
                                                                <p class="font-bold mb-1">Revision completed:</p>
                                                                <p>{{ $item->revision }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            @if($item->link_docs || $item->link_post)
                                            <button 
                                                class="text-xs text-orange-600 hover:text-orange-800 focus:outline-none inline-flex items-center"
                                                onclick="openRevisionModal('{{ $item->id }}', '{{ $item->keyword }}')"
                                                title="Suggest Revision"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 p-6 rounded-lg mx-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">You don't have any articles yet</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Create your first article now!</p>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('try-writing') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create New Article
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Create New Article -->
        <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 px-4">
            <a href="{{ route('keywords.create') }}" class="keywords-btn w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-base font-medium rounded-lg shadow-lg transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Keywords
            </a>
        </div>
    </div>
</div>

<!-- Revision Modal -->
<div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Suggest Revision</h3>
            <button onclick="closeRevisionModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="revisionForm">
            @csrf
            <input type="hidden" id="dashboard_item_id" name="dashboard_item_id" value="">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="keyword">
                    Keyword
                </label>
                <div id="keywordDisplay" class="p-2 bg-gray-100 rounded text-gray-800"></div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="note">
                    Revision Notes
                </label>
                <textarea 
                    id="note" 
                    name="note" 
                    rows="5" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Enter your revision suggestions..."
                    required
                ></textarea>
                <p class="text-gray-500 text-xs mt-1">Please provide specific details about what needs to be revised.</p>
            </div>
            
            <div id="revisionError" class="mb-4 text-red-500 text-sm hidden"></div>
            <div id="revisionSuccess" class="mb-4 text-green-500 text-sm hidden"></div>
            
            <div class="flex items-center justify-end">
                <button 
                    type="button" 
                    onclick="closeRevisionModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none mr-2"
                >
                    Cancel
                </button>
                <button 
                    type="button" 
                    onclick="submitRevision()" 
                    class="submit-btn px-4 py-2 rounded focus:outline-none border-2 border-green-700 font-bold shadow-md"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Thêm alpine.js nếu chưa có
if (typeof Alpine === 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        const alpineScript = document.createElement('script');
        alpineScript.setAttribute('src', 'https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js');
        alpineScript.setAttribute('defer', '');
        document.head.appendChild(alpineScript);
    });
}

function toggleInfo(elementId) {
    var element = document.getElementById(elementId);
    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function toggleIdeaModal(index) {
    var modal = document.getElementById('idea-modal-' + index);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        // Re-enable body scrolling
        document.body.style.overflow = 'auto';
    }
}

function toggleLinkModal(index) {
    var modal = document.getElementById('link-modal-' + index);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        // Prevent body scrolling when modal is open
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        // Re-enable body scrolling
        document.body.style.overflow = 'auto';
    }
}

function openRevisionModal(itemId, keyword) {
    document.getElementById('dashboard_item_id').value = itemId;
    document.getElementById('keywordDisplay').textContent = keyword;
    document.getElementById('note').value = '';
    document.getElementById('revisionError').classList.add('hidden');
    document.getElementById('revisionSuccess').classList.add('hidden');
    
    const modal = document.getElementById('revisionModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRevisionModal() {
    const modal = document.getElementById('revisionModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function submitRevision() {
    const form = document.getElementById('revisionForm');
    const formData = new FormData();
    const dashboard_item_id = document.getElementById('dashboard_item_id').value;
    const note = document.getElementById('note').value;
    const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    formData.append('dashboard_item_id', dashboard_item_id);
    formData.append('note', note);
    formData.append('_token', csrf_token);
    
    // Show processing state
    const submitBtn = form.querySelector('button[type="button"]:last-child');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    fetch('/revisions/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf_token,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('revisionSuccess').textContent = 'Revision submitted successfully';
            document.getElementById('revisionSuccess').classList.remove('hidden');
            document.getElementById('revisionError').classList.add('hidden');
            
            // Close modal after 2 seconds
            setTimeout(() => {
                closeRevisionModal();
                
                // Reload page to update dashboard data
                window.location.reload();
            }, 2000);
        } else {
            document.getElementById('revisionError').textContent = data.message || 'Failed to submit revision';
            document.getElementById('revisionError').classList.remove('hidden');
            document.getElementById('revisionSuccess').classList.add('hidden');
        }
    })
    .catch(error => {
        document.getElementById('revisionError').textContent = 'An error occurred: ' + error.message;
        document.getElementById('revisionError').classList.remove('hidden');
        document.getElementById('revisionSuccess').classList.add('hidden');
    })
    .finally(() => {
        // Restore submit button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// hCaptcha Integration
@if(session('show_captcha'))
    // Load hCaptcha script dynamically
    if (!document.querySelector('script[src*="hcaptcha.com"]')) {
        const hcaptchaScript = document.createElement('script');
        hcaptchaScript.setAttribute('src', 'https://js.hcaptcha.com/1/api.js');
        hcaptchaScript.setAttribute('async', '');
        hcaptchaScript.setAttribute('defer', '');
        document.head.appendChild(hcaptchaScript);
    }
@endif
</script>
@endsection
