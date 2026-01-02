<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PythonApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TestPythonApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:python-api {action=register : Action to test (register/sync)} {--url= : Optional URL to override the default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to Python API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $pythonApi = new PythonApiService();
        
        // Nếu có URL tùy chỉnh, sử dụng nó
        $apiUrl = $this->option('url') ?: env('PYTHON_API_URL');
        
        $this->info('Testing Python API connection...');
        $this->info('API URL: ' . $apiUrl);
        
        if ($action === 'register') {
            $this->testRegisterCustomer($pythonApi, $apiUrl);
        } elseif ($action === 'sync') {
            $this->testSyncDashboard($pythonApi, $apiUrl);
        } else {
            $this->error('Invalid action. Use "register" or "sync".');
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Test registerCustomer API
     */
    protected function testRegisterCustomer(PythonApiService $pythonApi, ?string $customUrl = null)
    {
        $this->info('Testing registerCustomer API...');
        
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'website' => 'http://example.com',
            'guidelines' => 'Test guidelines',
            'register_date' => now()->format('Y-m-d')
        ];
        
        $this->info('Sending data: ' . json_encode($testData));
        
        try {
            if ($customUrl) {
                $this->info('Using custom URL: ' . $customUrl);
                $apiUrl = $customUrl;
                $response = Http::timeout(30)->post("{$apiUrl}/api/register-customer", $testData);
                
                if ($response->successful()) {
                    $this->info('Success! Response: ' . $response->body());
                } else {
                    $this->error('API call failed. Status: ' . $response->status() . ', Body: ' . $response->body());
                }
            } else {
                $response = $pythonApi->registerCustomer($testData);
                
                if ($response) {
                    $this->info('Success! Response: ' . json_encode($response));
                } else {
                    $this->error('API call failed. Response was null.');
                }
            }
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
        }
    }
    
    /**
     * Test syncDashboard API
     */
    protected function testSyncDashboard(PythonApiService $pythonApi, ?string $customUrl = null)
    {
        $this->info('Testing syncDashboard API...');
        
        // Test with a fake sheet ID to see if the API responds
        $sheetId = '1234567890abcdef';
        
        $this->info('Using sheet ID: ' . $sheetId);
        
        try {
            if ($customUrl) {
                $this->info('Using custom URL: ' . $customUrl);
                $apiUrl = $customUrl;
                $response = Http::timeout(30)->post("{$apiUrl}/api/syns-dashboard", [
                    'sheet_id' => $sheetId
                ]);
                
                if ($response->successful()) {
                    $this->info('Success! Response: ' . $response->body());
                } else {
                    $this->error('API call failed. Status: ' . $response->status() . ', Body: ' . $response->body());
                }
            } else {
                $response = $pythonApi->syncDashboard($sheetId);
                
                if ($response) {
                    $this->info('Success! Response: ' . json_encode($response));
                } else {
                    $this->error('API call failed. Response was null.');
                }
            }
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
        }
    }
}
