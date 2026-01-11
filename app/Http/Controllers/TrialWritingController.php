<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrialRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\PythonApiService;
use App\Models\DashboardSheet;
use App\Models\DashboardItem;
use Illuminate\Support\Facades\Queue;

class TrialWritingController extends Controller
{
    // 📌 Hiển thị trang thử viết
    public function show(Request $request)
    {
        // Capture selected plan from welcome page pricing section
        $selectedPlan = $request->query('plan');

        if ($selectedPlan && in_array($selectedPlan, ['starter', 'professional', 'enterprise'])) {
            session(['selected_plan' => $selectedPlan]);
            Log::info('Plan selected from welcome page', [
                'plan' => $selectedPlan,
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
        }

        return view('try-writing', compact('selectedPlan'));  // Đảm bảo có file `resources/views/try-writing.blade.php`
    }

    // 📌 Xử lý bài viết thử nghiệm sau khi khách điền form
    public function processTrialPayment(Request $request)
    {
        try {
            // 🔒 Validate hCaptcha FIRST - chặn bot spam từ Ấn Độ
            $captchaValid = $this->validateCaptcha($request->input('h-captcha-response'));
            
            if (!$captchaValid) {
                Log::warning('hCaptcha failed on trial registration', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'user_agent' => $request->userAgent()
                ]);
                
                return redirect()->route('try-writing')
                    ->withInput()
                    ->withErrors(['captcha' => 'Please complete the captcha verification to continue.']);
            }

            // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
                'industry_type' => 'required|string|in:finance,health',
                'industry' => 'required|string|max:255',
                'keyword_option' => 'required|string|in:provided,analysis',
                'keywords' => 'nullable|string',
                'primary_keywords' => 'nullable|string',
                'competitor_links' => 'nullable|array',
                'competitor_links.*' => 'nullable|string',
            'notes' => 'nullable|string',
                'direct_posting' => 'nullable|boolean',
                'website_url' => 'nullable|string',
                'website_username' => 'nullable|string',
                'website_password' => 'nullable|string',
                'login_url' => 'nullable|string',
            ]);

            Log::info('Validation passed', $validatedData);

            // Kiểm tra nếu user đã có tài khoản hoặc tạo mới
            $user = User::where('email', $validatedData['email'])->first();
            $newPassword = null;
            
            if (!$user) {
                // Tạo mật khẩu ngẫu nhiên
                $newPassword = Str::random(12);
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($newPassword),
                ]);

                Log::info('New user created', ['user_id' => $user->id, 'email' => $user->email]);
            } else {
                Log::info('Existing user found', ['user_id' => $user->id, 'email' => $user->email]);
            }

            // Tự động đăng nhập user
            Auth::login($user);
            
            // Clean up competitor links array to remove empty values
            $competitorLinks = collect($validatedData['competitor_links'] ?? [])
                ->filter(function ($link) {
                    return !empty($link);
                })
                ->values()
                ->toArray();
                
            // Xử lý keywords: tách thành các từ khóa riêng biệt
            $keywordsString = $validatedData['keywords'] ?? '';
            $processedKeywords = $this->processKeywords($keywordsString);
            
            // Tạo bản ghi TrialRegistration với các trường mới
            $trial = TrialRegistration::create([
                'user_id' => $user->id,
                'industry_type' => $validatedData['industry_type'],
                'industry' => $validatedData['industry'],
                'keyword_option' => $validatedData['keyword_option'],
                'keywords' => $processedKeywords, // Sử dụng chuỗi đã xử lý
                'primary_keywords' => $validatedData['primary_keywords'] ?? '',
                'competitor_links' => $competitorLinks,
                'notes' => $validatedData['notes'] ?? '',
                'direct_posting' => isset($validatedData['direct_posting']) ? true : false,
                'website_url' => $validatedData['website_url'] ?? null,
                'website_username' => $validatedData['website_username'] ?? null,
                'website_password' => isset($validatedData['website_password']) ? 
                    encrypt($validatedData['website_password']) : null,
                'login_url' => $validatedData['login_url'] ?? '',
            ]);
            
            // Lưu validatedData vào session để dùng sau
            session(['validatedData' => $validatedData]);
            session(['trial_id' => $trial->id]);
            session(['user_id' => $user->id]);
            
            // PHÂN TÁCH CÁC TÁC VỤ NẶNG BẰNG QUEUE ĐỂ KHÔNG CHẶN NGƯỜI DÙNG
            // Các tác vụ sau sẽ chạy bất đồng bộ trong background:
            // 1. Gửi email thông báo mật khẩu (nếu là user mới)
            // 2. Tạo các keyword records
            // 3. Gọi API Python để đăng ký khách hàng
            dispatch(function() use ($trial, $keywordsString, $validatedData, $newPassword, $user) {
                // 1. Nếu là user mới, gửi email chứa mật khẩu
                if ($newPassword) {
                    try {
                        Log::info('Sending password email in background', ['email' => $user->email]);
                        
                        // Tạo nội dung HTML email chuyên nghiệp
                        $html = "
                        <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333;'>
                            <div style='background-color: #4f46e5; padding: 15px; text-align: center; color: white;'>
                                <h1 style='margin: 0;'>Welcome to TopWriterX!</h1>
                            </div>
                            <div style='padding: 20px; border: 1px solid #ddd; border-top: none;'>
                                <p>Hello <strong>{$validatedData['name']}</strong>,</p>
                                <p>Your account has been successfully created. Here are your login details:</p>
                                <div style='background-color: #f5f5f5; padding: 15px; margin: 15px 0; border-left: 4px solid #4f46e5;'>
                                    <p><strong>Email:</strong> {$validatedData['email']}</p>
                                    <p><strong>Password:</strong> {$newPassword}</p>
                                </div>
                                <p>Please keep this information secure. You can change your password after logging in.</p>
                                <p>If you have any questions, feel free to contact our support team.</p>
                                <p>Thank you for choosing TopWriterX!</p>
                                <p>Best regards,<br>The TopWriterX Team</p>
                            </div>
                            <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #777;'>
                                <p>This is an automated email, please do not reply.</p>
                            </div>
                        </div>
                        ";
                        
                        // Gửi email HTML
                        Mail::html($html, function ($message) use ($validatedData) {
                            $message->to($validatedData['email'])
                                   ->subject('Your New Account Details - TopWriterX');
                        });
                        
                        Log::info('Email sent successfully', ['email' => $validatedData['email']]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send email', [
                            'email' => $validatedData['email'],
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
                
                // 2. Tạo các keyword records
                $keywordArray = $this->keywordsToArray($keywordsString);
                if (count($keywordArray) > 0) {
                    Log::info('Creating keywords in background', ['count' => count($keywordArray)]);
                    
                    // Lưu từng từ khóa riêng lẻ vào bảng keywords
                    foreach ($keywordArray as $index => $keyword) {
                        $trial->keywords()->create([
                            'keyword' => $keyword,
                            'order' => $index + 1,
                            'status' => 'pending'
                        ]);
                    }
                    
                    Log::info('Individual keywords created', [
                        'trial_id' => $trial->id,
                        'keywords_count' => count($keywordArray)
                    ]);
                }
                
                // 3. Gọi API Python và tạo Dashboard
                try {
                    Log::info('Calling Python API in background', ['trial_id' => $trial->id]);
                    
                    $pythonApi = app(PythonApiService::class);
                    
                    // Chuyển đổi mảng competitor_links thành chuỗi các website đối thủ phân tách bởi dấu phẩy
                    $competitorWebsites = implode(', ', $validatedData['competitor_links'] ?? []);
                    
                    // Cập nhật dữ liệu gửi đến API Python theo định dạng mới
                    $registerData = [
                        'name' => $validatedData['name'],
                        'email' => $validatedData['email'],
                        'website' => $validatedData['website_url'] ?? '',
                        'guidelines' => $validatedData['notes'] ?? '',
                        'register_date' => now()->format('Y-m-d'),
                        'keyword' => $this->keywordsToArray($keywordsString),
                        'link_login' => $validatedData['login_url'] ?? '',
                        'username' => $validatedData['website_username'] ?? '',
                        'password' => $validatedData['website_password'] ?? '',
                        'competitor_website' => $competitorWebsites,
                        'credit' => $user->credits
                    ];

                    $apiResponse = $pythonApi->registerCustomer($registerData);
                    
                    if ($apiResponse && isset($apiResponse['status']) && $apiResponse['status'] === 'ok') {
                        // Lưu thông tin Google Sheet Dashboard
                        $dashboardSheet = new DashboardSheet([
                            'user_id' => $user->id,
                            'trial_registration_id' => $trial->id,
                            'sheet_id' => $apiResponse['sheet_id'],
                            'sheet_url' => $apiResponse['sheet_url']
                        ]);
                        $dashboardSheet->save();
                        
                        // Đồng bộ dữ liệu từ dashboard ngay lập tức
                        $this->syncDashboardData($dashboardSheet);
                        
                        Log::info('Dashboard sheet created and synced in background', [
                            'sheet_id' => $dashboardSheet->sheet_id,
                            'user_id' => $user->id
                        ]);
                    } else {
                        Log::error('Failed to register customer with Python API', [
                            'response' => $apiResponse
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error in background Python API call', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            })->afterResponse(); // Chạy sau khi đã trả response cho người dùng

            // Chuyển hướng ngay lập tức đến trang payment options thay vì đợi các tác vụ trên hoàn thành
            return redirect()->route('trial.payment.options');
        } catch (\Exception $e) {
            Log::error('Error in processTrialPayment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('try-writing')
                ->with('error', 'An error occurred while processing your request. Please try again.')
                ->withInput();
        }
    }

    /**
     * Xử lý chuỗi keywords để loại bỏ khoảng trắng thừa và định dạng lại
     * 
     * @param string $keywordsString
     * @return string
     */
    private function processKeywords(string $keywordsString): string
    {
        // Nếu chuỗi rỗng, trả về chuỗi rỗng
        if (empty($keywordsString)) {
            return '';
        }
        
        // Tách thành mảng các từ khóa
        $keywordArray = $this->keywordsToArray($keywordsString);
        
        // Ghép lại thành chuỗi, ngăn cách bởi dấu phẩy
        return implode(', ', $keywordArray);
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

    /**
     * Handle nurture lead for users not ready for premium services
     */
    public function nurtureLead(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'website_url' => 'nullable|string',
                'reason' => 'required|string'
            ]);

            // Log the nurture lead
            Log::info('Nurture lead captured', [
                'name' => $data['name'],
                'email' => $data['email'],
                'website_url' => $data['website_url'] ?? '',
                'reason' => $data['reason'],
                'timestamp' => now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Send email notification to admin
            try {
                $html = "
                <div style='max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333;'>
                    <div style='background-color: #f59e0b; padding: 15px; text-align: center; color: white;'>
                        <h1 style='margin: 0;'>Nurture Lead - Not Ready for Premium</h1>
                    </div>
                    <div style='padding: 20px; border: 1px solid #ddd; border-top: none;'>
                        <p><strong>A potential customer expressed interest but is not ready for premium services ($500+/month).</strong></p>
                        
                        <div style='background-color: #f5f5f5; padding: 15px; margin: 15px 0; border-left: 4px solid #f59e0b;'>
                            <p><strong>Name:</strong> {$data['name']}</p>
                            <p><strong>Email:</strong> {$data['email']}</p>
                            <p><strong>Website:</strong> " . ($data['website_url'] ?? 'Not provided') . "</p>
                            <p><strong>Reason:</strong> " . ucfirst(str_replace('_', ' ', $data['reason'])) . "</p>
                            <p><strong>Timestamp:</strong> " . now()->format('Y-m-d H:i:s T') . "</p>
                        </div>
                        
                        <p><strong>Suggested Follow-up Actions:</strong></p>
                        <ul>
                            <li>Add to nurture email sequence for budget-conscious businesses</li>
                            <li>Send case studies showing ROI for similar businesses</li>
                            <li>Offer lower-tier service options or trial packages</li>
                            <li>Schedule follow-up in 3-6 months</li>
                        </ul>
                        
                        <p>This lead should be added to your CRM with 'nurture' status and appropriate tags.</p>
                    </div>
                    <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #777;'>
                        <p>Automated notification from TopWriterX lead qualification system</p>
                    </div>
                </div>
                ";
                
                Mail::html($html, function ($message) use ($data) {
                    $message->to('dieuhanhweb@gmail.com')
                           ->subject('[Nurture Lead] Not Ready for Premium - ' . $data['name']);
                });
                
                Log::info('Nurture lead email sent successfully', ['email' => $data['email']]);
            } catch (\Exception $e) {
                Log::error('Failed to send nurture lead email', [
                    'email' => $data['email'],
                    'error' => $e->getMessage()
                ]);
            }

            // Save to Google Sheets (optional - using existing Python API if available)
            try {
                $pythonApi = app(PythonApiService::class);
                $sheetData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'website' => $data['website_url'] ?? '',
                    'register_date' => now()->format('Y-m-d'),
                    'notes' => 'Not ready for premium ($500+/month)',
                    'status' => 'nurture_lead',
                    'reason' => $data['reason']
                ];
                
                // Try to save to Google Sheets using the same method as qualified leads
                // but with special status indicating this is a nurture lead
                $apiResponse = $pythonApi->registerCustomer($sheetData);
                
                if ($apiResponse && isset($apiResponse['status']) && $apiResponse['status'] === 'ok') {
                    Log::info('Nurture lead saved to Google Sheets', [
                        'email' => $data['email'],
                        'sheet_response' => $apiResponse
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to save nurture lead to Google Sheets', [
                    'email' => $data['email'],
                    'error' => $e->getMessage()
                ]);
                // Don't fail the request if Google Sheets fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your interest. We\'ll keep your information on file.',
                'data' => [
                    'email' => $data['email'],
                    'status' => 'nurture_lead_saved'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing nurture lead', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    /**
     * Đồng bộ dữ liệu từ Google Sheet Dashboard
     */
    protected function syncDashboardData(DashboardSheet $dashboardSheet)
    {
        try {
            $pythonApi = app(PythonApiService::class);
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
                
                Log::info('Dashboard synced successfully', [
                    'sheet_id' => $dashboardSheet->sheet_id,
                    'items_count' => count($dashboardData)
                ]);
                
                return true;
            }
            
            Log::error('Failed to sync dashboard data', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'data' => $dashboardData
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Exception syncing dashboard', [
                'sheet_id' => $dashboardSheet->sheet_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * 🔒 Validate hCaptcha response - chống bot spam
     */
    private function validateCaptcha($captchaResponse): bool
    {
        if (!$captchaResponse) {
            Log::warning('hCaptcha: No response provided for trial registration', ['ip' => request()->ip()]);
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => env('HCAPTCHA_SECRET'),
                'response' => $captchaResponse,
                'remoteip' => request()->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('hCaptcha validation for trial registration', [
                'success' => $result['success'] ?? false,
                'ip' => request()->ip(),
                'error_codes' => $result['error-codes'] ?? []
            ]);
            
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('hCaptcha validation error in trial registration', [
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);
            
            // Nếu hCaptcha service bị lỗi, block để an toàn
            return false;
        }
    }
}