<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'dashboard_sheet_id',
        'keyword',
        'link_top',
        'idea',
        'guidelines',
        'link_docs',
        'link_post',
        'credit',
        'revision',
        'revision_status',
        'insights',
        'order',
    ];
    
    /**
     * Quan hệ với DashboardSheet
     */
    public function dashboardSheet()
    {
        return $this->belongsTo(DashboardSheet::class);
    }
} 