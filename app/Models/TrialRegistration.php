<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'industry_type',
        'industry',
        'keyword_option',
        'keywords',
        'primary_keywords',
        'competitor_links',
        'notes',
        'direct_posting',
        'website_url',
        'website_username',
        'website_password',
        'payment_completed',
        'payment_method',
        'payment_amount',
        'payment_transaction_id',
        'payment_capture_id',
        'payment_refunded',
        'refund_transaction_id',
        'refund_processed_at',
    ];

    protected $casts = [
        'competitor_links' => 'array',
        'direct_posting' => 'boolean',
        'payment_completed' => 'boolean',
        'payment_amount' => 'decimal:2',
        'payment_refunded' => 'boolean',
        'refund_processed_at' => 'datetime',
    ];

    /**
     * Mối quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mối quan hệ với Keyword
     */
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Lấy tất cả keywords đã tách thành mảng
     */
    public function getKeywordsArrayAttribute()
    {
        if (empty($this->keywords)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->keywords));
    }
} 