<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip', 'reason', 'block_public'];

    protected $casts = [
        'block_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** IP bị chặn thống kê (click/view) - theo user sở hữu brand/campaign */
    public static function isBlocked(string $ip, ?int $userId): bool
    {
        $query = static::where('ip', $ip);
        if ($userId !== null) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }

        return $query->exists();
    }

    /** IP bị chặn truy cập trang public (chỉ còn truy cập /admin) - chỉ admin set */
    public static function isBlockedFromPublic(string $ip): bool
    {
        return static::where('ip', $ip)->where('block_public', true)->exists();
    }

    /** Scope: chỉ record của user (admin thấy tất cả) */
    public function scopeForUser(Builder $query, ?int $userId, bool $isAdmin): Builder
    {
        if ($isAdmin) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }
}
