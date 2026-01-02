<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'dashboard_sheet_id',
        'dashboard_item_id',
        'keyword',
        'note',
        'synced_to_sheet',
        'api_response'
    ];

    /**
     * Các thuộc tính nên chuyển đổi
     *
     * @var array
     */
    protected $casts = [
        'synced_to_sheet' => 'boolean',
        'api_response' => 'array',
    ];

    /**
     * Lấy người dùng sở hữu revision này
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy dashboard sheet liên quan 
     */
    public function dashboardSheet()
    {
        return $this->belongsTo(DashboardSheet::class);
    }

    /**
     * Lấy dashboard item liên quan
     */
    public function dashboardItem()
    {
        return $this->belongsTo(DashboardItem::class);
    }
}
