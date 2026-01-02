<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\DashboardSheet;
use App\Models\DashboardItem;
use App\Services\PythonApiService;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current user
        $user = Auth::user();
        
        // Debug logging
        Log::info('Dashboard accessed', [
            'user_id' => $user->id ?? 'not logged in',
            'user_email' => $user->email ?? 'no email',
            'auth_check' => Auth::check()
        ]);
        
        // Lấy dashboard sheet của user
        $dashboardSheet = DashboardSheet::where('user_id', $user->id)->latest()->first();
        
        // Kiểm tra nếu dashboard sheet cần được đồng bộ lại
        if ($dashboardSheet && (!$dashboardSheet->last_synced_at || $dashboardSheet->last_synced_at->diffInMinutes(now()) > 60)) {
            $this->syncDashboardData($dashboardSheet);
        }
        
        // Lấy các dashboard items nếu có dashboard sheet
        $dashboardItems = [];
        if ($dashboardSheet) {
            $dashboardItems = DashboardItem::where('dashboard_sheet_id', $dashboardSheet->id)
                ->orderBy('order')
                ->get();
        }
        
        return view('dashboard', [
            'dashboardItems' => $dashboardItems,
            'dashboardSheet' => $dashboardSheet
        ]);
    }
    
    /**
     * Đồng bộ dữ liệu từ Google Sheet Dashboard
     */
    protected function syncDashboardData(DashboardSheet $dashboardSheet)
    {
        try {
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
                        'credit' => $item['credit'] ?? 0,
                        'order' => $index + 1,
                    ]);
                }
                
                // Cập nhật thời gian sync
                $dashboardSheet->last_synced_at = now();
                $dashboardSheet->save();
                
                Log::info('Dashboard synced successfully from controller', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'items_count' => count($dashboardData)
                ]);
                
                return true;
            }
            
            Log::error('Failed to sync dashboard data from controller', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'data' => $dashboardData
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Exception syncing dashboard from controller', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
}