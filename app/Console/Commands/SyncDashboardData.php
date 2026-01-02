<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DashboardSheet;
use App\Models\DashboardItem;
use App\Services\PythonApiService;

class SyncDashboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync dashboard data from Google Sheets via Python API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting dashboard synchronization...');
        
        // Lấy tất cả dashboard sheets từ database
        $dashboardSheets = DashboardSheet::all();
        
        if ($dashboardSheets->isEmpty()) {
            $this->info('No dashboard sheets to synchronize.');
            return 0;
        }
        
        $pythonApi = new PythonApiService();
        $syncCount = 0;
        
        foreach ($dashboardSheets as $dashboardSheet) {
            $this->info("Syncing dashboard {$dashboardSheet->id} for user {$dashboardSheet->user_id}");
            
            try {
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
                    
                    $this->info("Successfully synced dashboard {$dashboardSheet->id} with " . count($dashboardData) . " items");
                    $syncCount++;
                } else {
                    $this->error("Failed to sync dashboard {$dashboardSheet->id} - Invalid or empty data returned");
                    Log::error('Failed to sync dashboard data', [
                        'sheet_id' => $dashboardSheet->sheet_id,
                        'data' => $dashboardData
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Error syncing dashboard {$dashboardSheet->id}: " . $e->getMessage());
                Log::error('Exception while syncing dashboard', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        $this->info("Dashboard synchronization completed! Synced {$syncCount} dashboards.");
        return 0;
    }
} 