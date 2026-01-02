<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;
    
    /**
     * Các thuộc tính có thể gán
     */
    protected $fillable = [
        'trial_registration_id',
        'keyword',
        'order',
        'is_completed',
        'status',
        'result_url',
    ];
    
    /**
     * Các thuộc tính cần ép kiểu
     */
    protected $casts = [
        'is_completed' => 'boolean',
    ];
    
    /**
     * Quan hệ với TrialRegistration
     */
    public function trialRegistration()
    {
        return $this->belongsTo(TrialRegistration::class);
    }
    
    /**
     * Scope lấy các từ khóa chưa hoàn thành
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false)->where('status', 'pending');
    }
    
    /**
     * Scope lấy các từ khóa đang xử lý
     */
    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false)->where('status', 'in_progress');
    }
    
    /**
     * Scope lấy các từ khóa đã hoàn thành
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true)->where('status', 'completed');
    }
}
