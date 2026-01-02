@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-indigo-100 to-blue-50 min-h-screen py-8">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-10 py-8 text-white">
                <h2 class="text-3xl font-bold text-center">Tạo bài viết chất lượng cao</h2>
                <p class="mt-2 text-indigo-100 text-center">Chỉ cần vài thông tin đơn giản, chúng tôi sẽ giúp bạn tạo ra bài viết tuyệt vời</p>
                <div class="w-24 h-1 bg-indigo-300 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('trial-writing.store') }}" class="space-y-8">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Vui lòng kiểm tra lại thông tin:</p>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-700 flex items-center">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 text-sm">1</div>
                            Lựa chọn ngành nghề của bạn
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 transition-all hover:shadow-md hover:border-indigo-200">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="radio" name="industry_type" value="finance" class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500" {{ old('industry_type') == 'finance' ? 'checked' : '' }}>
                                    <div>
                                        <span class="block text-lg font-medium text-gray-800">Tài chính - Đầu tư - Crypto</span>
                                        <span class="block text-sm text-gray-500 mt-1">Bài viết về thị trường tài chính, đầu tư, tiền điện tử và các xu hướng kinh tế</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-indigo-100 transition-all hover:shadow-md hover:border-indigo-200">
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="radio" name="industry_type" value="health" class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500" {{ old('industry_type') == 'health' ? 'checked' : '' }}>
                                    <div>
                                        <span class="block text-lg font-medium text-gray-800">Sức khỏe - Y tế - Thẩm mỹ</span>
                                        <span class="block text-sm text-gray-500 mt-1">Bài viết về sức khỏe, dinh dưỡng, làm đẹp và các xu hướng chăm sóc sức khỏe</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-700 flex items-center">
                            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 text-sm">2</div>
                            Thông tin chi tiết
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Lĩnh vực cụ thể:</label>
                                <input type="text" id="industry" name="industry" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('industry') }}" placeholder="Ví dụ: Thị trường chứng khoán, Phẫu thuật thẩm mỹ, v.v.">
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên:</label>
                                <input type="text" id="name" name="name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('name') }}" placeholder="Họ và tên của bạn">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                                <input type="email" id="email" name="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('email') }}" placeholder="Email của bạn">
                            </div>
                            
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Công ty (không bắt buộc):</label>
                                <input type="text" id="company" name="company" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('company') }}" placeholder="Tên công ty của bạn (nếu có)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 flex justify-center">
                        <button type="submit" class="w-full md:w-2/3 lg:w-1/2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all hover:-translate-y-1 duration-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Bắt đầu tạo bài viết
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="bg-gray-50 p-4 rounded-xl shadow-sm">
                        <p class="text-center text-sm text-gray-600">
                            Chúng tôi cam kết bảo mật thông tin của bạn và chỉ sử dụng để tạo bài viết.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 