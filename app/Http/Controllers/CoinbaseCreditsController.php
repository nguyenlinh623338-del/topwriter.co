<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CoinbasePaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Transaction;
use Exception;

class CoinbaseCreditsController extends Controller
{
    protected $coinbase;

    public function __construct(CoinbasePaymentService $coinbase)
    {
        $this->coinbase = $coinbase;
    }

    /**
     * Checkout $600 for 30 credits with Coinbase (Using successful trial formula)
     */
    public function checkout()
    {
        try {
            Log::info('Starting Coinbase credits checkout', ['user_id' => Auth::id()]);
            
            if (!auth()->check()) {
                return redirect()->route('credits.checkout')->with('error', 'Please login before payment.');
            }

            // Amount for 30 credits: $600
            $amount = 600.00;
            
            // Create Coinbase charge using the successful method
            $charge = $this->coinbase->createCreditsPayment($amount);
            Log::info('Coinbase credits charge created', ['charge_id' => $charge['id']]);

                         // Create transaction record with fallback for missing columns
             try {
                 $transaction = new Transaction();
                 $transaction->user_id = Auth::id();
                 $transaction->amount = $amount;
                 $transaction->type = 'credits';
                 $transaction->status = 'pending';
                 
                 // Only set these fields if columns exist (production safety)
                 if (Schema::hasColumn('transactions', 'payment_method')) {
                     $transaction->payment_method = 'coinbase';
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
                     $transaction->payment_transaction_id = $charge['id'];
                 }
                 
                 $transaction->save();
                 
             } catch (Exception $e) {
                 Log::error('Failed to create Coinbase transaction record', [
                     'error' => $e->getMessage(),
                     'charge_id' => $charge['id']
                 ]);
                 
                 // Fallback: Create minimal transaction record
                 $transaction = new Transaction([
                     'user_id' => Auth::id(),
                     'amount' => $amount,
                     'type' => 'credits',
                     'status' => 'pending'
                 ]);
                 $transaction->save();
             }

            // Save transaction ID to session
            session(['coinbase_credits_transaction_id' => $transaction->id]);
            Log::info('Saved Coinbase credits transaction to session', [
                'transaction_id' => $transaction->id,
                'charge_id' => $charge['id']
            ]);

            // Redirect to Coinbase checkout
            Log::info('Redirecting to Coinbase credits checkout page', ['url' => $charge['hosted_url']]);
            return redirect()->away($charge['hosted_url']);
            
        } catch (Exception $e) {
            Log::error('Coinbase credits checkout failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'Failed to create Coinbase payment. Please try again.');
        }
    }

    /**
     * Handle successful credits payment (Using successful trial formula)
     */
    public function success(Request $request)
    {
        try {
            Log::info('Coinbase credits success callback received');
            
            // Get transaction from session (like trial payment)
            $transactionId = session('coinbase_credits_transaction_id');
            if ($transactionId) {
                $transaction = Transaction::find($transactionId);
                
                if ($transaction) {
                    // Update transaction status with column checks
                    $updateData = ['status' => 'completed'];
                    
                    if (Schema::hasColumn('transactions', 'payment_status')) {
                        $updateData['payment_status'] = 'completed';
                    }
                    if (Schema::hasColumn('transactions', 'payment_completed_at')) {
                        $updateData['payment_completed_at'] = now();
                    }
                    
                    $transaction->update($updateData);
                    
                    Log::info('Coinbase credits transaction updated', [
                        'transaction_id' => $transactionId,
                        'update_data' => $updateData
                    ]);

                    // Add credits to user (NO REFUND for credits)
                    $user = User::find($transaction->user_id);
                    if ($user) {
                        $oldCredits = $user->credits;
                        $user->credits += 30;
                        $user->save();
                        
                        Log::info('Credits added to user account via Coinbase', [
                            'user_id' => $user->id,
                            'previous_credits' => $oldCredits,
                            'added_credits' => 30,
                            'new_total_credits' => $user->credits
                        ]);
                    }
                }
            }

            // Clear session
            session()->forget('coinbase_credits_transaction_id');
            
            return redirect()->route('credits.success')->with('success', '30 credits have been added to your account via Coinbase!');
            
        } catch (Exception $e) {
            Log::error('Coinbase credits success processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        try {
            Log::info('Coinbase credits payment cancelled by user');
            
            // Get transaction ID from session
            $transactionId = session('coinbase_credits_transaction_id');
            
            if ($transactionId) {
                // Get transaction from database
                $transaction = Transaction::find($transactionId);
                
                if ($transaction) {
                    // Update transaction status
                    $transaction->status = 'cancelled';
                    $transaction->payment_status = 'cancelled';
                    $transaction->save();
                    
                    Log::info('Coinbase credits transaction marked as cancelled', ['transaction_id' => $transactionId]);
                }
                
                // Clear session data
                session()->forget('coinbase_credits_transaction_id');
            }
            
            return redirect()->route('credits.checkout')->with('error', 'Bạn đã hủy thanh toán Coinbase.');
        } catch (\Exception $e) {
            Log::error('Error handling Coinbase credits cancellation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('credits.checkout')->with('error', 'Đã xảy ra lỗi khi xử lý hủy thanh toán.');
        }
    }
} 