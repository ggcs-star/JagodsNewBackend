<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Exception;

class SecurityAuditLog extends Model
{
    use HasUuids;

    public $timestamps = false; // Sirf created_at use hoga

    protected $fillable = [
        'user_id', 'user_device_id', 'device_id', 'event_category', 
        'action_type', 'api_endpoint', 'ip_address', 'severity_score', 
        'response_status', 'request_id', 'metadata', 'created_at'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * 🚨 ENTERPRISE SECURITY: IMMUTABLE LOGS
     * Yeh event listner ensure karega ki log database me ek baar 
     * write hone ke baad kabhi update ya delete na ho sake.
     */
    protected static function booted()
    {
        static::updating(function ($log) {
            throw new Exception("Security Audit Logs are immutable and cannot be updated.");
        });

        static::deleting(function ($log) {
            throw new Exception("Security Audit Logs are immutable and cannot be deleted.");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(UserDevice::class, 'user_device_id');
    }
}