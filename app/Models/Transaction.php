<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subscription_id',
        'amount',
        'type',
        'status',
        'payment_method',
        'payment_status',
        'transaction_type',
        'payment_transaction_id',
        'credits',
        'payment_completed_at',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription associated with the transaction.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
