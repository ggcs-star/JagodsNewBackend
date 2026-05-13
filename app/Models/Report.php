<?php

namespace App\Models;

use App\Enums\ReportStatus;
use App\Models\User;
use App\Models\Order;
use App\Models\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends BaseModel implements HasMedia
{

    use InteractsWithMedia;

    protected $table       = 'reports';
    protected $fillable    = ['order_id', 'description'];

    protected $auditColumn       = true;

    public function getImageAttribute()
    {
        if (!empty($this->getFirstMediaUrl('report'))) {
            return asset($this->getFirstMediaUrl('report'));
        }
        return asset('backend/images/default/banner.jpg');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == ReportStatus::REFUND) {
            return '<span class="db-table-badge text-purple-600 bg-purple-100">' . trans('report_statuses.' . ReportStatus::REFUND) . '</span>';
        } else if ($this->status == ReportStatus::CANCEL) {
            return '<span class="db-table-badge text-yellow-600 bg-yellow-100">' . trans('report_statuses.' . ReportStatus::CANCEL) . '</span>';
        } else if ($this->status == ReportStatus::PENDING) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('report_statuses.' . ReportStatus::PENDING) . '</span>';
        }
    }

}
