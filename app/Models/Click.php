<?php

namespace App\Models;

use App\Models\Concerns\ExcludesVietnamFromAdminStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Click extends Model
{
    use ExcludesVietnamFromAdminStats;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'ip',
        'user_agent',
        'referer',
        'sub_id',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
