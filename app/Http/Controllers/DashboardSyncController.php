<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\DashboardSheet;
use App\Models\DashboardItem;
use App\Services\PythonApiService;

class DashboardSyncController extends Controller
{
    /**
     * Đồng bộ dữ liệu từ Google Sheet Dashboard với Rate Limiting
     */
    public function sync(Request $request)
    {
        $user = Auth::user();
        
        // Kiểm tra rate limiting
        $rateLimitCheck = $this->checkRateLimit($user->id);
        
        if ($rateLimitCheck['requires_captcha']) {
            // Validate hCaptcha
            $captchaValid = $this->validateCaptcha($request->input('h-captcha-response'));
            
            if (!$captchaValid) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Vui lòng xác thực hCaptcha để tiếp tục.')
                    ->with('show_captcha', true);
            }
        }
        
        // Kiểm tra dashboard sheet
        $dashboardSheet = DashboardSheet::where('user_id', $user->id)->latest()->first();
        
        if (!$dashboardSheet) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Không tìm thấy dashboard sheet để đồng bộ.');
        }
        
        try {
            // Gọi API để đồng bộ dữ liệu
            $pythonApi = new PythonApiService();
            $dashboardData = $pythonApi->syncDashboard($dashboardSheet->sheet_id);
            
            if ($dashboardData && is_array($dashboardData)) {
                // Xóa các items cũ
                DashboardItem::where('dashboard_sheet_id', $dashboardSheet->id)->delete();
                
                // Thêm các items mới
                foreach ($dashboardData as $index => $item) {
                    DashboardItem::create([
                        'dashboard_sheet_id' => $dashboardSheet->id,
                        'keyword' => $item['keyword'] ?? '',
                        'link_top' => $item['link_top'] ?? null,
                        'idea' => $item['idea'] ?? null,
                        'guidelines' => $item['guidelines'] ?? null,
                        'link_docs' => $item['link_docs'] ?? null,
                        'link_post' => $item['link_post'] ?? null,
                        'revision' => $item['revision'] ?? null,
                        'revision_status' => $item['status'] ?? null,
                        'insights' => $item['insights'] ?? null,
                        'order' => $index + 1,
                    ]);
                }
                
                // Cập nhật thời gian sync và tracking
                $dashboardSheet->last_synced_at = now();
                $dashboardSheet->save();
                
                // Update rate limiting session
                $this->updateRateLimitSession($user->id);
                
                Log::info('Dashboard synced successfully with rate limiting', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'items_count' => count($dashboardData),
                    'user_id' => $user->id,
                    'required_captcha' => $rateLimitCheck['requires_captcha']
                ]);
                
                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Đã cập nhật dashboard từ Google Sheet thành công! ✅');
            }
            
            Log::error('Failed to sync dashboard data with rate limiting', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'data' => $dashboardData,
                'user_id' => $user->id
            ]);
            
            return redirect()
                ->route('dashboard')
                ->with('error', 'Không thể đồng bộ dữ liệu từ Google Sheet. Vui lòng thử lại.');
                
        } catch (\Exception $e) {
            Log::error('Exception syncing dashboard with rate limiting', [
                'sheet_id' => $dashboardSheet->sheet_id ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
            
            return redirect()
                ->route('dashboard')
                ->with('error', 'Đã xảy ra lỗi khi cập nhật dashboard: ' . $e->getMessage());
        }
    }
    
    /**
     * Kiểm tra rate limiting cho user
     */
    private function checkRateLimit($userId)
    {
        $sessionKey = "dashboard_sync_rate_limit_{$userId}";
        $syncData = session($sessionKey, []);
        
        $now = now();
        $lastSync = isset($syncData['last_sync']) ? $syncData['last_sync'] : null;
        $syncCount = $syncData['count'] ?? 0;
        
        // Nếu lần đầu hoặc đã qua 5 phút - cho phép sync free
        if (!$lastSync || $now->diffInMinutes($lastSync) >= 5) {
            return [
                'requires_captcha' => false,
                'can_sync' => true,
                'message' => 'Được phép sync miễn phí'
            ];
        }
        
        // Nếu sync lại trong 5 phút - yêu cầu captcha
        return [
            'requires_captcha' => true,
            'can_sync' => true,
            'message' => 'Yêu cầu xác thực hCaptcha',
            'minutes_left' => 5 - $now->diffInMinutes($lastSync)
        ];
    }
    
    /**
     * Cập nhật rate limiting session
     */
    private function updateRateLimitSession($userId)
    {
        $sessionKey = "dashboard_sync_rate_limit_{$userId}";
        $syncData = session($sessionKey, []);
        
        session([
            $sessionKey => [
                'last_sync' => now(),
                'count' => ($syncData['count'] ?? 0) + 1
            ]
        ]);
    }
    
    /**
     * Validate hCaptcha
     */
    private function validateCaptcha($captchaResponse)
    {
        if (!$captchaResponse) {
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => env('HCAPTCHA_SECRET'),
                'response' => $captchaResponse,
                'remoteip' => request()->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('hCaptcha validation result', [
                'success' => $result['success'] ?? false,
                'error_codes' => $result['error-codes'] ?? []
            ]);
            
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('hCaptcha validation error', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
