<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PayPalService
{
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;

    // Số tiền tối thiểu (trial fee) mà PayPal chấp nhận cho live environment
    private $minimalAmount = 1.00;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        // Sử dụng base URL từ config, mặc định là sandbox nếu không có
        $this->baseUrl = config('services.paypal.base_url', 'https://api-m.sandbox.paypal.com');
        
        // Debug environment configuration
        $isSandbox = strpos($this->baseUrl, 'sandbox') !== false;
        Log::info('PayPal Service initialized', [
            'environment' => $isSandbox ? 'sandbox' : 'live',
            'base_url' => $this->baseUrl,
            'client_id_set' => !empty($this->clientId),
            'client_secret_set' => !empty($this->clientSecret),
            'client_id_prefix' => substr($this->clientId, 0, 10) . '...',
        ]);
        
        $this->accessToken = '';//$this->getAccessToken();
    }

    /**
     * Retrieve PayPal Access Token
     */
    private function getAccessToken()
    {
        try {
            // Kiểm tra client ID và client secret trước khi sử dụng
            if (empty($this->clientId) || empty($this->clientSecret)) {
                Log::error('PayPal credentials not found', [
                    'clientId' => !empty($this->clientId) ? 'set' : 'empty',
                    'clientSecret' => !empty($this->clientSecret) ? 'set' : 'empty'
                ]);
                throw new Exception('PayPal credentials not configured correctly.');
            }
            
            $response = Http::asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                $json = $response->json();
                if (isset($json['access_token'])) {
                    return $json['access_token'];
                } else {
                    Log::error('PayPal Access Token Error: access_token not found', [
                        'response' => $response->body()
                    ]);
                    throw new Exception('Failed to retrieve PayPal access token.');
                }
            }

            Log::error('PayPal Access Token Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
                'url' => "{$this->baseUrl}/v1/oauth2/token",
                'client_id_length' => strlen($this->clientId)
            ]);

            throw new Exception('Failed to retrieve PayPal access token.');
        
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Access Token Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('PayPal authentication failed.');
        }
    }

    /**
     * Create a PayPal Payment
     *
     * Chấp nhận mảng cấu hình hoặc số tiền trực tiếp
     */
    public function createPayment($data)
    {
        try {
            // Kiểm tra cấu hình trước khi xử lý
            if (empty($this->clientId) || empty($this->clientSecret) || empty($this->baseUrl)) {
                Log::error('PayPal configuration missing', [
                    'clientId' => !empty($this->clientId) ? 'set' : 'empty',
                    'clientSecret' => !empty($this->clientSecret) ? 'set' : 'empty',
                    'baseUrl' => !empty($this->baseUrl) ? $this->baseUrl : 'empty'
                ]);
                throw new Exception('PayPal configuration is incomplete');
            }
            
            // Kiểm tra xem data là mảng hay số
            if (is_array($data)) {
                $payload = $data;
                
                // Nếu là mảng, kiểm tra xem có khai báo giá trị đúng cách không
                if (isset($payload['purchase_units'][0]['amount']['value'])) {
                    $amount = (float) $payload['purchase_units'][0]['amount']['value'];
                    
                    // Kiểm tra giá trị tối thiểu
                    if ($amount < $this->minimalAmount) {
                        $payload['purchase_units'][0]['amount']['value'] = number_format($this->minimalAmount, 2, '.', '');
                    }
                }
            } else {
                // Xử lý trường hợp data là số tiền (để tương thích ngược)
                $amount = (float) $data;
                
                // Nếu amount < minimalAmount, đặt thành minimalAmount để đảm bảo payload hợp lệ
                if ($amount < $this->minimalAmount) {
                    $amount = $this->minimalAmount;
                }

                // Thiết lập payload cho yêu cầu tạo đơn hàng
                $payload = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'amount' => [
                                'currency_code' => 'USD',
                                'value' => number_format($amount, 2, '.', '')
                            ]
                        ]
                    ],
                    'application_context' => [
                        // Sử dụng biến config, nếu chưa có thì sử dụng default URL
                        'return_url' => config('services.paypal.return_url', url('/paypal-success')),
                        'cancel_url' => config('services.paypal.cancel_url', url('/paypal-cancel'))
                    ]
                ];
            }

            // Log payload để debug
            Log::info('Creating PayPal Payment with payload:', $payload);
            
            // Đảm bảo token hợp lệ
            if (empty($this->accessToken)) {
                Log::warning('No PayPal access token, attempting to get one now');
                $this->accessToken = $this->getAccessToken();
            }

            $response = Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/v2/checkout/orders", $payload);

            if ($response->successful()) {
                // Lưu Order ID của đơn hàng vừa tạo
                $responseData = $response->json();
                if (isset($responseData['id'])) {
                    Log::info('PayPal order created', ['order_id' => $responseData['id']]);
                }
                
                return $responseData;
            }

            // Nếu không thành công, log toàn bộ body của response
            $errorBody = $response->body();
            Log::error('PayPal Payment Creation Error', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $errorBody,
                'url' => "{$this->baseUrl}/v2/checkout/orders",
                'payload' => $payload
            ]);

            // Log cụ thể cho lỗi 403
            if ($response->status() === 403) {
                $isSandbox = strpos($this->baseUrl, 'sandbox') !== false;
                $errorDetails = [
                    'environment' => $isSandbox ? 'sandbox' : 'live',
                    'client_id_starts_with' => substr($this->clientId, 0, 10),
                    'possible_causes' => [
                        'Using sandbox credentials with live environment (or vice versa)',
                        'Live account not approved by PayPal yet',
                        'IP address not whitelisted',
                        'Invalid credentials for current environment'
                    ]
                ];
                
                Log::error('PayPal 403 Forbidden Error', $errorDetails);
                
                if (!$isSandbox) {
                    Log::error('LIVE ENVIRONMENT DETECTED: Make sure your app is approved for live payments');
                }
            }

            throw new Exception('PayPal payment creation failed with status code: ' . $response->status());
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Payment Creation Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('PayPal payment request failed: ' . $e->getMessage());
        }
    }

    /**
     * Capture a PayPal Payment
     */
    public function capturePayment($orderId)
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->withBody(json_encode(new \stdClass()), 'application/json')  // gửi empty JSON object
                ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->body();
            Log::error('PayPal Capture Payment Error', [
                'status'  => $response->status(),
                'headers' => $response->headers(),
                'body'    => $errorBody,
                'url' => "{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture"
            ]);

            throw new Exception('Failed to capture PayPal payment.');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Capture Payment Exception', ['message' => $e->getMessage()]);
            throw new Exception('Failed to capture PayPal payment.');
        }
    }

    /**
     * Get Order Details from PayPal
     */
    public function getOrderDetails($orderId)
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/v2/checkout/orders/{$orderId}");

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->body();
            Log::error('PayPal Get Order Details Error', [
                'status' => $response->status(),
                'body'   => $errorBody,
                'url' => "{$this->baseUrl}/v2/checkout/orders/{$orderId}"
            ]);

            throw new Exception('Failed to retrieve PayPal order details.');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Get Order Details Exception', ['message' => $e->getMessage()]);
            throw new Exception('Error retrieving PayPal order details.');
        }
    }

    /**
     * Refund a captured PayPal Payment
     *
     * Sau khi capture thành công, bạn có thể refund giao dịch bằng cách gọi hàm này.
     * @param string $captureId    Capture ID được lấy từ response của capturePayment()
     * @param float  $amount       Số tiền refund (thường là toàn bộ số tiền đã thu)
     * @param string $currency     Mã tiền tệ, mặc định USD
     * @return array               Response từ PayPal
     */
    public function refundPayment($captureId, $amount, $currency = 'USD')
    {
        try {
            $payload = [
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format($amount, 2, '.', '')
                ]
            ];

            Log::info('Initiating refund with payload:', $payload);

            $response = Http::withToken($this->accessToken)
                ->post("{$this->baseUrl}/v2/payments/captures/{$captureId}/refund", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->body();
            Log::error('PayPal Refund Payment Error', [
                'status' => $response->status(),
                'body'   => $errorBody,
                'url' => "{$this->baseUrl}/v2/payments/captures/{$captureId}/refund"
            ]);

            throw new Exception('Failed to refund PayPal payment.');
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Refund Payment Exception', ['message' => $e->getMessage()]);
            throw new Exception('Refund request failed.');
        }
    }
}