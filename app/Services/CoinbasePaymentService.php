<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CoinbasePaymentService
{
    // Số tiền tối thiểu mà Coinbase chấp nhận (ví dụ: 0.10 USD)
    private $minimalAmount = 0.10;

    public function createPayment($amount)
    {
        // Nếu amount <= 0, ép thành số tiền tối thiểu
        if ($amount <= 0) {
            $amount = $this->minimalAmount;
        }

        $response = Http::withHeaders([
            'X-CC-Api-Key' => env('COINBASE_API_KEY'),
            'X-CC-Version' => '2018-03-22',
        ])->post('https://api.commerce.coinbase.com/charges', [
            'name' => 'Trial Article Payment',
            'description' => 'Trial payment for article. A minimal charge will be applied and then refunded.',
            'pricing_type' => 'fixed_price',
            'local_price' => [
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'USD'
            ],
            'metadata' => [
                'customer_id' => auth()->id(),
                'customer_email' => auth()->user()->email
            ],
            'redirect_url' => url('/crypto/success'),
            'cancel_url' => url('/crypto/cancel'),
        ]);

        $json = $response->json();

        if (!isset($json['data'])) {
            Log::error('Coinbase Payment Creation Error', [
                'response' => $json
            ]);
            throw new Exception('Coinbase payment creation failed: ' . json_encode($json));
        }

        return $json['data'];
    }

    /**
     * Create Coinbase payment for credits ($600 for 30 credits)
     */
    public function createCreditsPayment($amount)
    {
        $response = Http::withHeaders([
            'X-CC-Api-Key' => env('COINBASE_API_KEY'),
            'X-CC-Version' => '2018-03-22',
        ])->post('https://api.commerce.coinbase.com/charges', [
            'name' => '30 Credits Package',
            'description' => 'Premium credits package - 30 credits for high-quality medical content writing',
            'pricing_type' => 'fixed_price',
            'local_price' => [
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'USD'
            ],
            'metadata' => [
                'customer_id' => auth()->id(),
                'customer_email' => auth()->user()->email,
                'package_type' => 'credits',
                'credits_amount' => '30'
            ],
            'redirect_url' => url('/credits/crypto/success'),
            'cancel_url' => url('/credits/crypto/cancel'),
        ]);

        $json = $response->json();

        if (!isset($json['data'])) {
            Log::error('Coinbase Credits Payment Creation Error', [
                'response' => $json
            ]);
            throw new Exception('Coinbase credits payment creation failed: ' . json_encode($json));
        }

        return $json['data'];
    }
}