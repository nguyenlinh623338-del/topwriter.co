<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PythonApiService;

class TestApiController extends Controller
{
    /**
     * Chuyển đổi chuỗi keywords thành mảng các từ khóa riêng biệt
     * 
     * @param string $keywordsString
     * @return array
     */
    private function keywordsToArray(string $keywordsString): array
    {
        // Nếu chuỗi rỗng, trả về mảng rỗng
        if (empty($keywordsString)) {
            return [];
        }
        
        // Thay thế tất cả dấu xuống dòng và dấu chấm phẩy bằng dấu phẩy
        $keywordsString = str_replace(["\r\n", "\r", "\n", ";"], ',', $keywordsString);
        
        // Tách chuỗi thành mảng dựa vào dấu phẩy
        $keywords = explode(',', $keywordsString);
        
        // Loại bỏ khoảng trắng thừa và các phần tử rỗng
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords, function($keyword) {
            return !empty($keyword);
        });
        
        // Trả về mảng các từ khóa đã xử lý
        return array_values($keywords);
    }

    /**
     * Kiểm tra định dạng JSON gửi đến API
     */
    public function testJsonFormat(Request $request)
    {
        $keywordsString = $request->input('keywords', 'keyword1, keyword2, keyword3');
        
        $registerData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'website' => 'https://example.com',
            'guidelines' => 'Some guidelines',
            'register_date' => now()->format('Y-m-d'),
            'keywords' => $this->keywordsToArray($keywordsString)
        ];
        
        // Chuyển đổi mảng thành JSON để hiển thị định dạng cuối cùng sẽ gửi đi
        $jsonData = json_encode($registerData, JSON_PRETTY_PRINT);
        
        // Log dữ liệu
        Log::info('Test API JSON format', [
            'registerData' => $registerData,
            'jsonData' => $jsonData
        ]);
        
        // Hiển thị dữ liệu JSON
        return response()->json([
            'registerData' => $registerData,
            'jsonData' => $jsonData,
            'raw_curl_command' => 'curl -X POST https://lackey.ccz.es/api/register-customer -d \'' . addslashes(json_encode($registerData)) . '\' -H "Content-Type: application/json"'
        ]);
    }
}
