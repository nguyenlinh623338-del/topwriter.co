<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PythonApiService
{
    protected $baseUrl;
    
    public function __construct()
    {
        // Nếu đang chạy trên môi trường local, sử dụng localhost thay vì lackey.ccz.es
        if (app()->environment('local') && env('PYTHON_API_URL_LOCAL')) {
            $this->baseUrl = env('PYTHON_API_URL_LOCAL', 'http://localhost:5000');
        } else {
            $this->baseUrl = env('PYTHON_API_URL', 'https://lackey.ccz.es');
        }
    }
    
    /**
     * Gọi API đăng ký khách hàng và tạo Google Sheet Dashboard
     * 
     * @param array $data Dữ liệu khách hàng (name, email, website, guidelines, register_date)
     * @return array|null Trả về thông tin sheet_url và sheet_id nếu thành công, null nếu thất bại
     */
    public function registerCustomer(array $data)
    {
        try {
            Log::info('Sending data to Python API registerCustomer', $data);
            
            // Tăng timeout lên 30 giây cho API có độ trễ cao
            $response = Http::timeout(30)->post("{$this->baseUrl}/api/register-customer", $data);
            
            if ($response->successful()) {
                Log::info('Python API registerCustomer success', $response->json());
                return $response->json();
            }
            
            Log::error('Python API registerCustomer error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Python API registerCustomer exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
    
    /**
     * Đồng bộ dữ liệu từ Google Sheet Dashboard
     * 
     * @param string $dashboardId ID của Google Sheet Dashboard
     * @return array|null Trả về dữ liệu từ dashboard nếu thành công, null nếu thất bại
     */
    public function syncDashboard(string $dashboardId)
    {
        try {
            Log::info('Syncing dashboard from Python API', ['dashboard_id' => $dashboardId]);
            
            // Tăng timeout lên 30 giây cho API có độ trễ cao
            $response = Http::timeout(30)->post("{$this->baseUrl}/api/syns-dashboard", [
                'sheet_id' => $dashboardId
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Python API syncDashboard success', [
                    'item_count' => is_array($data) ? count($data) : 'not an array'
                ]);
                return $data;
            }
            
            Log::error('Python API syncDashboard error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Python API syncDashboard exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
    
    /**
     * Thêm từ khóa mới vào dashboard
     * 
     * @param string $email Email của user
     * @param array $keywords Danh sách keywords mới
     * @param int $credits Số credit còn lại sau khi thêm
     * @return array|null Trả về kết quả từ API
     */
    public function addKeywords(string $email, array $keywords, int $credits)
    {
        try {
            $data = [
                'email' => $email,
                'new_keywords' => $keywords,
                'credit' => $credits
            ];
            
            Log::info('Calling Python API add-keyword', [
                'email' => $email,
                'keywords_count' => count($keywords),
                'new_credit' => $credits
            ]);
            
            // Tăng timeout lên 30 giây cho API có độ trễ cao
            $response = Http::timeout(30)->post("{$this->baseUrl}/api/add-keyword", $data);
            
            if ($response->successful()) {
                Log::info('Python API add-keyword success', $response->json());
                return $response->json();
            }
            
            Log::error('Python API add-keyword error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Python API add-keyword exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Cập nhật góp ý chỉnh sửa vào Google Sheet Dashboard
     *
     * @param string $sheetId ID của Google Sheet Dashboard
     * @param array $revisions Mảng chứa các góp ý cần cập nhật, mỗi góp ý chứa keyword và note
     * @return array|null Trả về kết quả từ API
     */
    public function updateRevision(string $sheetId, array $revisions)
    {
        try {
            $data = [
                'sheet_id' => $sheetId,
                'revisions' => $revisions
            ];
            
            Log::info('Calling Python API update-revision', [
                'sheet_id' => $sheetId,
                'revisions_count' => count($revisions)
            ]);
            
            // Tăng timeout lên 30 giây cho API có độ trễ cao
            $response = Http::timeout(30)->post("{$this->baseUrl}/api/update-revision", $data);
            
            if ($response->successful()) {
                Log::info('Python API update-revision success', $response->json());
                return $response->json();
            }
            
            Log::error('Python API update-revision error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Python API update-revision exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
} 