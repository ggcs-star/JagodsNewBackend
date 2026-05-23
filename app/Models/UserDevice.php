<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserDevice extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'user_id', 'device_id', 'fingerprint_hash', 'device_name', 
        'browser', 'platform', 'app_version', 'last_ip_address', 
        'last_country', 'last_city', 'user_agent', 'trust_level', 
        'failed_attempts', 'last_active_at'
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(SecurityAuditLog::class);
    }
}