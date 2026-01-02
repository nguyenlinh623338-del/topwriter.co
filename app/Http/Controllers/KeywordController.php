<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\DashboardSheet;
use App\Services\PythonApiService;

class KeywordController extends Controller
{
    /**
     * Display keyword form
     */
    public function create()
    {
        // Kiểm tra user đã đăng nhập
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm từ khóa mới.');
        }

        // Kiểm tra user có dashboard sheet chưa
        $dashboardSheet = DashboardSheet::where('user_id', $user->id)->latest()->first();
        if (!$dashboardSheet) {
            return redirect()->route('dashboard')->with('error', 'Bạn cần tạo bài viết đầu tiên trước khi thêm từ khóa mới.');
        }

        // Kiểm tra số credit còn lại
        $availableCredits = $user->credits;

        return view('keywords.create', [
            'availableCredits' => $availableCredits,
            'dashboardSheet' => $dashboardSheet
        ]);
    }

    /**
     * Process keyword submission
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'keywords' => 'required|string',
        ]);

        // Lấy thông tin user hiện tại
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm từ khóa mới.');
        }

        // Xử lý chuỗi keyword thành mảng
        $keywordsString = $request->input('keywords');
        $keywordArray = $this->keywordsToArray($keywordsString);
        
        // Kiểm tra số lượng keyword đã nhập
        $keywordCount = count($keywordArray);
        if ($keywordCount == 0) {
            return redirect()->back()->with('error', 'Vui lòng nhập ít nhất một từ khóa.');
        }

        // Kiểm tra credit còn đủ không
        if ($user->credits < $keywordCount) {
            return redirect()->back()->with('error', 'Bạn không đủ credit để thêm ' . $keywordCount . ' từ khóa này. Hiện bạn có ' . $user->credits . ' credit.');
        }

        // Lấy dashboard sheet của user
        $dashboardSheet = DashboardSheet::where('user_id', $user->id)->latest()->first();
        if (!$dashboardSheet) {
            return redirect()->route('dashboard')->with('error', 'Không tìm thấy dashboard của bạn. Vui lòng tạo bài viết đầu tiên.');
        }

        // Bắt đầu giao tiếp với Python API
        try {
            $pythonApi = new PythonApiService();
            
            // Gọi API add-keyword với danh sách keyword mới
            $remainingCredits = $user->credits - $keywordCount;
            $response = $pythonApi->addKeywords($user->email, $keywordArray, $remainingCredits);
            
            if (!$response || !isset($response['status']) || $response['status'] !== 'ok') {
                Log::error('Python API add-keyword failed', [
                    'response' => $response,
                    'user_id' => $user->id
                ]);
                return redirect()->back()->with('error', 'Không thể thêm từ khóa mới. Vui lòng thử lại sau.');
            }
            
            // Trừ credit trên hệ thống
            $user->credits = $remainingCredits;
            $user->save();
            
            Log::info('Credits updated after adding keywords', [
                'user_id' => $user->id,
                'used_credits' => $keywordCount,
                'remaining_credits' => $remainingCredits
            ]);
            
            // Đồng bộ lại dashboard từ Google Sheet
            $this->syncDashboard($dashboardSheet);
            
            return redirect()->route('dashboard')
                ->with('success', 'Đã thêm ' . $keywordCount . ' từ khóa mới thành công! Đã sử dụng ' . $keywordCount . ' credit.');
        } catch (\Exception $e) {
            Log::error('Error when adding keywords', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);
            
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Đồng bộ dữ liệu từ Google Sheet Dashboard
     */
    protected function syncDashboard(DashboardSheet $dashboardSheet)
    {
        try {
            $pythonApi = new PythonApiService();
            $dashboardData = $pythonApi->syncDashboard($dashboardSheet->sheet_id);
            
            if ($dashboardData && is_array($dashboardData)) {
                // Xóa các items cũ
                \App\Models\DashboardItem::where('dashboard_sheet_id', $dashboardSheet->id)->delete();
                
                // Thêm các items mới
                foreach ($dashboardData as $index => $item) {
                    \App\Models\DashboardItem::create([
                        'dashboard_sheet_id' => $dashboardSheet->id,
                        'keyword' => $item['keyword'] ?? '',
                        'link_top' => $item['link_top'] ?? null,
                        'idea' => $item['idea'] ?? null,
                        'guidelines' => $item['guidelines'] ?? null,
                        'link_docs' => $item['link_docs'] ?? null,
                        'link_post' => $item['link_post'] ?? null,
                        'credit' => $item['credit'] ?? 0,
                        'order' => $index + 1,
                    ]);
                }
                
                // Cập nhật thời gian sync
                $dashboardSheet->last_synced_at = now();
                $dashboardSheet->save();
                
                Log::info('Dashboard synced successfully after adding keywords', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'items_count' => count($dashboardData)
                ]);
                
                return true;
            }
            
            Log::error('Failed to sync dashboard data after adding keywords', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'data' => $dashboardData
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Exception syncing dashboard after adding keywords', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

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
}
