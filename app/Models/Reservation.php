<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Shipu\Watchable\Traits\WatchableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\SlugOptions;
use DB;

class Reservation extends BaseModel implements HasMedia
{
    use WatchableTrait, InteractsWithMedia;
    protected $table       = 'reservations';
    protected $auditColumn       = true;
    protected $guarded     = ['id'];
    protected $casts = [
        'status' => 'int',
    ];
    protected $fakeColumns = [];

    public function creator()
    {
        return $this->morphTo();
    }

    public function editor()
    {
        return $this->morphTo();
    }

    public function table()
    {
        return $this->belongsTo(Table::class)->with('restaurant');
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class,'time_slot_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getStatusNameAttribute()
    {
        if ($this->status == ReservationStatus::ACCEPT) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('reservation_status.' . $this->status) . '</span>';
        } elseif ($this->status == ReservationStatus::PENDING) {
            return '<span class="db-table-badge text-yellow-600 bg-yellow-100">' . trans('reservation_status.' . $this->status) . '</span>';
        } elseif ($this->status == ReservationStatus::CANCEL) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('reservation_status.' . $this->status) . '</span>';
        } elseif ($this->status == ReservationStatus::REJECT) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('reservation_status.' . $this->status) . '</span>';
        } else {
            return '<span class="db-table-badge text-black bg-gray-200">' . trans('reservation_status.' . $this->status) . '</span>';
        }
    }
}
