@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
                <div class="relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
                    <div class="relative px-8 py-12 text-center">
                        <h1 class="text-4xl font-bold text-white mb-4">🎫 Support Request</h1>
                        <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                            Having issues with the dashboard or payments? We'll help you right away!
                        </p>
                        <div class="w-28 h-1.5 bg-indigo-300 mx-auto mt-6 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Error/Success Messages -->
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-sm mb-6" role="alert">
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

            <!-- Ticket Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                            Basic Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Subject -->
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-medium mb-3">Issue Subject *</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" 
                                    class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" 
                                    placeholder="e.g., PayPal payment error when purchasing credits" required>
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-3">Priority Level *</label>
                                <select name="priority" required 
                                    class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm">
                                    <option value="">Select priority level</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low - Minor issue</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium - Affects usage</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High - Cannot use</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent - Money/data loss</option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Problem Description -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                            Issue Description
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Description -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-3">Detailed problem description *</label>
                                <textarea name="description" rows="4" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" 
                                    placeholder="Please describe the problem you're experiencing in as much detail as possible...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Steps to Reproduce -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-3">Steps to reproduce (optional)</label>
                                <textarea name="steps_to_reproduce" rows="3"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" 
                                    placeholder="Step 1: Go to dashboard page&#10;Step 2: Click Update Dashboard button&#10;Step 3: Error appears...">{{ old('steps_to_reproduce') }}</textarea>
                                @error('steps_to_reproduce')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Expected Behavior -->
                                <div>
                                    <label class="block text-gray-700 font-medium mb-3">Expected result (optional)</label>
                                    <textarea name="expected_behavior" rows="3"
                                        class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" 
                                        placeholder="e.g., Dashboard updates successfully and displays new data">{{ old('expected_behavior') }}</textarea>
                                    @error('expected_behavior')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Actual Behavior -->
                                <div>
                                    <label class="block text-gray-700 font-medium mb-3">Actual result (optional)</label>
                                    <textarea name="actual_behavior" rows="3"
                                        class="w-full border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm" 
                                        placeholder="e.g., Page shows 500 error and cannot continue">{{ old('actual_behavior') }}</textarea>
                                    @error('actual_behavior')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-8 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            📤 Submit Support Request
                        </button>
                        <a href="{{ route('dashboard') }}" 
                            class="flex-1 bg-gray-100 text-gray-700 py-4 px-8 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 font-bold text-lg text-center transition-all duration-300">
                            ↩️ Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>

            <!-- Help Text -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h4 class="font-bold text-blue-800 mb-3">💡 Tips for faster support:</h4>
                <ul class="text-blue-700 space-y-2 text-sm">
                    <li>• Provide detailed information about the issue</li>
                    <li>• Include screenshots if possible (send via email reply)</li>
                    <li>• Choose the appropriate priority level</li>
                    <li>• Describe steps to reproduce the error</li>
                    <li>• We will respond within 24 hours</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 