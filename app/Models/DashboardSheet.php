<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardSheet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'trial_registration_id',
        'sheet_id',
        'sheet_url',
        'last_synced_at',
    ];
    
    protected $casts = [
        'last_synced_at' => 'datetime',
    ];
    
    /**
     * Quan hệ với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Quan hệ với TrialRegistration
     */
    public function trialRegistration()
    {
        return $this->belongsTo(TrialRegistration::class);
    }
    
    /**
     * Quan hệ với DashboardItem
     */
    public function items()
    {
        return $this->hasMany(DashboardItem::class);
    }
} 