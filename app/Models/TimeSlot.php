<?php

namespace App\Models;

use App\Enums\Status;
use Shipu\Watchable\Traits\WatchableTrait;

class TimeSlot extends BaseModel
{
    use WatchableTrait;

    protected $table       = 'time_slots';
    protected $auditColumn       = true;
    protected $fillable    = ['start_time', 'end_time', 'restaurant_id', 'status'];
    protected $casts = [
        'status' => 'int',
    ];

    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class,'time_slot_id');
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == Status::ACTIVE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('statuses.' . Status::ACTIVE) . '</span>';
        } else if ($this->status == Status::INACTIVE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('statuses.' . Status::INACTIVE) . '</span>';
        }
    }

}
