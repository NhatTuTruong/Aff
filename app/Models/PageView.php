<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'ip',
        'user_agent',
        'referer',
        'session_id',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'time_on_page',
        'is_bounce',
    ];

    protected $casts = [
        'is_bounce' => 'boolean',
        'time_on_page' => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get device type from user agent
     */
    public static function getDeviceType($userAgent): string
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);
        
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        }
        
        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    /**
     * Get browser from user agent
     */
    public static function getBrowser($userAgent): string
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false && strpos($userAgent, 'edg') === false) {
            return 'Chrome';
        }
        if (strpos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        }
        if (strpos($userAgent, 'safari') !== false && strpos($userAgent, 'chrome') === false) {
            return 'Safari';
        }
        if (strpos($userAgent, 'edg') !== false) {
            return 'Edge';
        }
        if (strpos($userAgent, 'opera') !== false || strpos($userAgent, 'opr') !== false) {
            return 'Opera';
        }
        
        return 'Other';
    }

    /**
     * Get OS from user agent
     */
    public static function getOS($userAgent): string
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'windows') !== false) {
            return 'Windows';
        }
        if (strpos($userAgent, 'mac') !== false) {
            return 'macOS';
        }
        if (strpos($userAgent, 'linux') !== false) {
            return 'Linux';
        }
        if (strpos($userAgent, 'android') !== false) {
            return 'Android';
        }
        if (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'iOS';
        }
        
        return 'Other';
    }
}
