<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Revision;
use App\Models\DashboardItem;
use App\Models\DashboardSheet;
use App\Services\PythonApiService;

class RevisionController extends Controller
{
    /**
     * Lưu góp ý chỉnh sửa và gọi API để cập nhật Google Sheet
     */
    public function store(Request $request)
    {
        // Xác thực yêu cầu
        $request->validate([
            'dashboard_item_id' => 'required|exists:dashboard_items,id',
            'note' => 'required|string|min:5|max:1000',
        ]);
        
        try {
            // Lấy thông tin người dùng và dashboard_item
            $user = Auth::user();
            $dashboardItem = DashboardItem::findOrFail($request->dashboard_item_id);
            $dashboardSheet = DashboardSheet::findOrFail($dashboardItem->dashboard_sheet_id);
            
            // Tạo revision mới
            $revision = new Revision([
                'user_id' => $user->id,
                'dashboard_sheet_id' => $dashboardSheet->id,
                'dashboard_item_id' => $dashboardItem->id,
                'keyword' => $dashboardItem->keyword,
                'note' => $request->note,
                'synced_to_sheet' => false,
            ]);
            
            // Lưu vào cơ sở dữ liệu
            $revision->save();
            
            Log::info('Revision created successfully', [
                'id' => $revision->id,
                'user_id' => $user->id,
                'keyword' => $dashboardItem->keyword
            ]);
            
            // Gọi API để cập nhật Google Sheet
            $pythonApi = new PythonApiService();
            $response = $pythonApi->updateRevision(
                $dashboardSheet->sheet_id,
                [
                    [
                        'keyword' => $dashboardItem->keyword,
                        'note' => $request->note
                    ]
                ]
            );
            
            // Cập nhật trạng thái đồng bộ và lưu response
            if ($response && isset($response['status']) && $response['status'] === 'ok') {
                $revision->synced_to_sheet = true;
                $revision->api_response = $response;
                $revision->save();
                
                // Đồng bộ ngược lại dashboard từ Google Sheet
                $this->syncDashboard($dashboardSheet);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Đã cập nhật góp ý chỉnh sửa thành công',
                    'revision' => $revision
                ]);
            } else {
                // Lưu response lỗi
                $revision->api_response = $response ?? ['error' => 'Không nhận được phản hồi từ API'];
                $revision->save();
                
                Log::error('Failed to update revision to Google Sheet', [
                    'revision_id' => $revision->id,
                    'response' => $response
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã lưu góp ý chỉnh sửa nhưng không thể cập nhật lên Google Sheet',
                    'revision' => $revision
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error storing revision', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
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
                
                Log::info('Dashboard synced successfully after updating revision', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'items_count' => count($dashboardData)
                ]);
                
                return true;
            }
            
            Log::error('Failed to sync dashboard data after updating revision', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'data' => $dashboardData
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Exception syncing dashboard after updating revision', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
}
