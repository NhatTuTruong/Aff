<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignPostSync extends Model
{
    protected $fillable = [
        'domain',
        'campaign_id',
        'platforms',
        'type',
        'response_status',
        'posted_at',
    ];

    protected $casts = [
        'platforms' => 'array',
        'posted_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function postedDomains(): array
    {
        return static::query()
            ->whereNotNull('domain')
            ->where('domain', '!=', '')
            ->distinct()
            ->pluck('domain')
            ->map(fn ($d) => strtolower(trim((string) $d)))
            ->filter()
            ->values()
            ->all();
    }
}
