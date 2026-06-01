<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserDevice extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = [
        'user_id', 'device_id', 'fingerprint_hash', 'device_name', 'device_type', 
        'browser', 'platform', 'app_version', 'language', 'user_agent',
        
        'last_ip_address', 'last_country', 'country_code', 'last_city', 
        'timezone', 'lat', 'lon', 
        
        'trust_level', 'risk_score', 'risk_reason', 'vpn_detected', 
        'proxy_detected', 'is_emulator', 'failed_attempts', 
        
        'login_count', 'is_current', 'last_active_at', 'last_login_at', 
        'trusted_at', 'blocked_at'
    ];

    protected $casts = [
        'last_active_at'  => 'datetime',
        'last_login_at'   => 'datetime',
        'trusted_at'      => 'datetime',
        'blocked_at'      => 'datetime',
        
        'risk_reason'     => 'array',
        
        'vpn_detected'    => 'boolean',
        'proxy_detected'  => 'boolean',
        'is_emulator'     => 'boolean',
        'is_current'      => 'boolean',
        
        'risk_score'      => 'integer',
        'login_count'     => 'integer',
        'failed_attempts' => 'integer',
        'lat'             => 'decimal:8', 
        'lon'             => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(SecurityAuditLog::class);
    }
    public function sessions()
    {
        return $this->hasMany(DeviceSession::class, 'user_device_id');
    }
}