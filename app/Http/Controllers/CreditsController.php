<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Exception;

class CreditsController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    /**
     * Display checkout page for credits
     */
    public function checkout()
    {
        return view('credits.checkout', [
            'amount' => 600,
            'credits' => 30
        ]);
    }

    /**
     * Confirmation page before payment
     */
    public function confirm()
    {
        return view('credits.confirm', [
            'amount' => 600,
            'credits' => 30
        ]);
    }

    /**
     * Process PayPal payment for credits (Using successful trial payment formula)
     */
    public function processPayPal()
    {
        try {
            Log::info('Starting PayPal checkout for credits', [
                'user_id' => Auth::id(),
                'amount' => 600.00
            ]);
            
            // Tạo payment với return URL riêng cho credits
            $paymentData = $this->paypalService->createPayment([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '600.00'
                        ],
                        'description' => '30 Credits Package - Premium Content Writing'
                    ]
                ],
                'application_context' => [
                    'return_url' => route('credits.paypal.success'),
                    'cancel_url' => route('credits.paypal.cancel')
                ]
            ]);
            
            if (!isset($paymentData['id'])) {
                throw new Exception('Failed to create PayPal payment');
            }
            
            $orderId = $paymentData['id'];
            Log::info('PayPal credits order created', ['order_id' => $orderId]);
            
                         // Tạo transaction record với fallback cho missing columns
             try {
                $transaction = new Transaction();
                $transaction->user_id = Auth::id();
                $transaction->amount = 600.00;
                 $transaction->type = 'credits';
                 $transaction->status = 'pending';
                 
                 // Only set these fields if columns exist (production safety)
                 if (Schema::hasColumn('transactions', 'payment_method')) {
                $transaction->payment_method = 'paypal';
                 }
                 if (Schema::hasColumn('transactions', 'transaction_type')) {
                $transaction->transaction_type = 'credits';
                 }
                 if (Schema::hasColumn('transactions', 'payment_status')) {
                $transaction->payment_status = 'pending';
                 }
                 if (Schema::hasColumn('transactions', 'credits')) {
                $transaction->credits = 30;
                 }
                 if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                     $transaction->payment_transaction_id = $orderId;
                 }
                 
                $transaction->save();
                 
             } catch (Exception $e) {
                 Log::error('Failed to create transaction record', [
                     'error' => $e->getMessage(),
                     'order_id' => $orderId
                 ]);
                 
                 // Fallback: Create minimal transaction record
                 $transaction = new Transaction([
                     'user_id' => Auth::id(),
                     'amount' => 600.00,
                     'type' => 'credits',
                     'status' => 'pending'
                 ]);
                 $transaction->save();
             }

                // Lưu ID giao dịch vào session
                session(['credits_transaction_id' => $transaction->id]);
            Log::info('Saved credits transaction to session', [
                    'transaction_id' => $transaction->id, 
                'order_id' => $orderId
                ]);
                
            // Tìm approval URL từ response
                $approvalUrl = null;
            if (isset($paymentData['links'])) {
                foreach ($paymentData['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        $approvalUrl = $link['href'];
                        break;
                    }
                }
                }
                
                if ($approvalUrl) {
                    Log::info('Redirecting to PayPal approval page', ['url' => $approvalUrl]);
                    return redirect($approvalUrl);
                }
            
            throw new Exception('PayPal approval URL not found');
            
        } catch (Exception $e) {
            Log::error('PayPal credits checkout failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('credits.checkout')
                ->with('error', 'Unable to initiate PayPal payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle PayPal payment success (Using successful trial payment formula)
     */
    public function paypalSuccess(Request $request)
    {
        try {
            $orderId = $request->query('token');
            Log::info('Credits PayPal success callback received', ['order_id' => $orderId]);

            if (!$orderId) {
                return redirect()->route('credits.checkout')->with('error', 'Payment token not found.');
            }

            // Get order details from PayPal (like trial payment)
            $orderDetails = $this->paypalService->getOrderDetails($orderId);
            Log::info('PayPal credits order details retrieved', ['status' => $orderDetails['status']]);

            if ($orderDetails['status'] === 'APPROVED') {
                // Capture payment (like trial payment)
                $captureResponse = $this->paypalService->capturePayment($orderId);
                Log::info('PayPal credits payment captured', ['capture_response' => $captureResponse]);
            
                // Get transaction from session
            $transactionId = session('credits_transaction_id');
                if ($transactionId) {
            $transaction = Transaction::find($transactionId);
                    
                    if ($transaction) {
                        // Update transaction status with column checks
                        $updateData = ['status' => 'completed'];
            
                        if (Schema::hasColumn('transactions', 'payment_status')) {
                            $updateData['payment_status'] = 'completed';
                        }
                        if (Schema::hasColumn('transactions', 'payment_transaction_id')) {
                            $updateData['payment_transaction_id'] = $orderId;
                        }
                        if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                            $updateData['payment_completed_at'] = now();
                        }
                        
                        $transaction->update($updateData);

                        Log::info('Credits transaction updated', [
                            'transaction_id' => $transactionId,
                            'update_data' => $updateData
                        ]);

                        // Add credits to user (NO REFUND for credits)
            $user = User::find($transaction->user_id);
                        if ($user) {
            $oldCredits = $user->credits;
                            $user->credits += 30;
            $user->save();

            Log::info('Credits added to user account', [
                'user_id' => $user->id,
                'previous_credits' => $oldCredits,
                'added_credits' => 30,
                'new_total_credits' => $user->credits
            ]);
                        }
                    }
                }

                // Clear session
            session()->forget('credits_transaction_id');

                return redirect()->route('credits.success')->with('success', '30 credits have been added to your account!');
            }

            return redirect()->route('credits.checkout')->with('error', 'Payment was not approved.');
            
        } catch (Exception $e) {
            Log::error('Credits PayPal success processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('credits.checkout')->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle PayPal payment cancellation
     */
    public function paypalCancel()
    {
        // Get transaction ID from session
        $transactionId = session('credits_transaction_id');
        
        if ($transactionId) {
            // Get transaction from database
            $transaction = Transaction::find($transactionId);
            
            if ($transaction) {
                // Update transaction status
                $transaction->status = 'cancelled';
                $transaction->save();
            }
            
            // Clear session data
            session()->forget('credits_transaction_id');
        }

        return redirect()->route('dashboard')->with('error', 'Payment was cancelled.');
    }

    /**
     * Display success page after successful payment
     */
    public function success()
    {
        return view('credits.success', [
            'credits' => 30
        ]);
    }
} 