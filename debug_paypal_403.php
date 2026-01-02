<?php
/**
 * Debug script để kiểm tra lỗi 403 PayPal Live Environment
 * Chạy script này để kiểm tra các vấn đề có thể gây ra lỗi 403
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

class PayPalDebugger
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    
    public function __construct()
    {
        // Lấy credentials từ env
        $this->clientId = env('PAYPAL_CLIENT_ID');
        $this->clientSecret = env('PAYPAL_CLIENT_SECRET');
        $this->baseUrl = env('PAYPAL_BASE_URL', 'https://api-m.paypal.com');
    }
    
    public function checkEnvironment()
    {
        echo "=== KIỂM TRA MÔI TRƯỜNG PAYPAL ===\n";
        echo "Base URL: " . $this->baseUrl . "\n";
        echo "Environment: " . (strpos($this->baseUrl, 'sandbox') !== false ? 'SANDBOX' : 'LIVE') . "\n";
        echo "Client ID: " . substr($this->clientId, 0, 10) . "..." . "\n";
        echo "Client Secret Length: " . strlen($this->clientSecret) . "\n";
        echo "\n";
    }
    
    public function testAccessToken()
    {
        echo "=== KIỂM TRA ACCESS TOKEN ===\n";
        
        try {
            $response = Http::asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);
                
            if ($response->successful()) {
                echo "✅ Access token lấy thành công\n";
                $json = $response->json();
                echo "Token type: " . ($json['token_type'] ?? 'unknown') . "\n";
                echo "Expires in: " . ($json['expires_in'] ?? 'unknown') . " seconds\n";
                return $json['access_token'] ?? null;
            } else {
                echo "❌ Lỗi lấy access token\n";
                echo "Status: " . $response->status() . "\n";
                echo "Response: " . $response->body() . "\n";
                return null;
            }
        } catch (Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "\n";
            return null;
        }
        
        echo "\n";
    }
    
    public function testCreateMinimalOrder($accessToken)
    {
        echo "=== KIỂM TRA TẠO ĐỚN HÀNG TỐI THIỂU ===\n";
        
        if (!$accessToken) {
            echo "❌ Không có access token để test\n";
            return;
        }
        
        // Test với số tiền tối thiểu 1.00 USD thay vì 0.10 USD
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '1.00'  // Tăng lên 1.00 USD
                    ],
                    'description' => 'Test order for debugging 403 error'
                ]
            ],
            'application_context' => [
                'return_url' => 'https://topwriter.co/paypal-success',
                'cancel_url' => 'https://topwriter.co/paypal-cancel'
            ]
        ];
        
        try {
            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/v2/checkout/orders", $payload);
                
            if ($response->successful()) {
                echo "✅ Đơn hàng tạo thành công\n";
                $data = $response->json();
                echo "Order ID: " . ($data['id'] ?? 'unknown') . "\n";
                echo "Status: " . ($data['status'] ?? 'unknown') . "\n";
            } else {
                echo "❌ Lỗi tạo đơn hàng\n";
                echo "Status: " . $response->status() . "\n";
                echo "Response: " . $response->body() . "\n";
                
                if ($response->status() === 403) {
                    $this->analyze403Error($response->json());
                }
            }
        } catch (Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    public function analyze403Error($errorData)
    {
        echo "\n=== PHÂN TÍCH LỖI 403 ===\n";
        
        if (isset($errorData['details'])) {
            foreach ($errorData['details'] as $detail) {
                $issue = $detail['issue'] ?? 'UNKNOWN';
                $description = $detail['description'] ?? 'No description';
                
                echo "Issue: $issue\n";
                echo "Description: $description\n";
                
                switch ($issue) {
                    case 'PERMISSION_DENIED':
                        echo "🔧 Giải pháp: Business account chưa được approve cho live payments\n";
                        echo "   - Đăng nhập PayPal Developer Console\n";
                        echo "   - Kiểm tra app status và submit để review\n";
                        break;
                        
                    case 'BUSINESS_VALIDATION_ERROR':
                        echo "🔧 Giải pháp: Business account chưa verify hoàn tất\n";
                        echo "   - Hoàn tất business verification trong PayPal account\n";
                        break;
                        
                    case 'INVALID_REQUEST':
                        echo "🔧 Giải pháp: Request format không đúng\n";
                        echo "   - Kiểm tra payload và headers\n";
                        break;
                        
                    default:
                        echo "🔧 Giải pháp: Liên hệ PayPal support hoặc kiểm tra documentation\n";
                }
                echo "\n";
            }
        }
    }
    
    public function checkBusinessAccountStatus()
    {
        echo "=== HƯỚNG DẪN KIỂM TRA BUSINESS ACCOUNT ===\n";
        echo "1. Đăng nhập vào https://developer.paypal.com\n";
        echo "2. Chọn ứng dụng của bạn\n";
        echo "3. Kiểm tra các mục sau:\n";
        echo "   ✓ App Status: Phải là 'Live' và 'Approved'\n";
        echo "   ✓ Business Account: Phải được verify hoàn tất\n";
        echo "   ✓ API Permissions: Cần có 'Accept payments' permission\n";
        echo "   ✓ Webhook URLs: Phải match với domain hiện tại\n";
        echo "\n";
        echo "4. Nếu app chưa được approve:\n";
        echo "   - Click 'Submit for Review'\n";
        echo "   - Cung cấp đầy đủ thông tin business\n";
        echo "   - Chờ PayPal review (có thể mất vài ngày)\n";
        echo "\n";
    }
    
    public function recommendedSolutions()
    {
        echo "=== GIẢI PHÁP KHUYẾN NGHỊ ===\n";
        echo "1. Thay đổi số tiền test từ 0.10 USD lên 1.00 USD:\n";
        echo "   - Một số live account có minimum amount requirement\n";
        echo "\n";
        echo "2. Kiểm tra Business Account verification:\n";
        echo "   - Đăng nhập PayPal business account\n";
        echo "   - Hoàn tất tất cả bước verification\n";
        echo "\n";
        echo "3. Whitelist server IP:\n";
        echo "   - Thêm IP server vào PayPal Developer Console\n";
        echo "   - Current server IP: " . $_SERVER['SERVER_ADDR'] ?? 'Unknown' . "\n";
        echo "\n";
        echo "4. Kiểm tra App permissions:\n";
        echo "   - Đảm bảo app có đủ permissions cho live payments\n";
        echo "\n";
    }
    
    public function run()
    {
        $this->checkEnvironment();
        $accessToken = $this->testAccessToken();
        $this->testCreateMinimalOrder($accessToken);
        $this->checkBusinessAccountStatus();
        $this->recommendedSolutions();
    }
}

// Chạy debug
echo "PayPal Live Environment Debug Tool\n";
echo "================================\n\n";

$debugger = new PayPalDebugger();
$debugger->run(); 