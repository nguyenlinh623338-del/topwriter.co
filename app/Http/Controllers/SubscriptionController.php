<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Transaction;

class SubscriptionController extends Controller
{
    public function purchasePremium(Request $request)
    {
        // Xử lý thanh toán $600
        $payment = $this->processPayment($request, 600);
        
        if ($payment->success) {
            // Tạo subscription record
            $subscription = Subscription::create([
                'user_id' => auth()->id(),
                'package_type' => 'premium',
                'amount_paid' => 600,
                'credits_added' => 30,
                'status' => 'active'
            ]);

            // Thêm credit cho user
            auth()->user()->addCredits(30);

            // Lưu transaction
            Transaction::create([
                'user_id' => auth()->id(),
                'subscription_id' => $subscription->id,
                'amount' => 600,
                'type' => 'credit_purchase',
                'status' => 'completed'
            ]);
        }
    }

    private function processPayment(Request $request, $amount)
    {
        // Giả lập xử lý thanh toán
        return (object) ['success' => true];
    }
}
