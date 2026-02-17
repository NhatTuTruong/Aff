<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = ['ip', 'reason'];

    public static function isBlocked(string $ip): bool
    {
        return static::where('ip', $ip)->exists();
    }
}
