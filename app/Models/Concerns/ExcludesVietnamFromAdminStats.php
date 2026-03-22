<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Thống kê admin: không tính view/click có country = Vietnam (theo GeoIP, ví dụ ip-api).
 */
trait ExcludesVietnamFromAdminStats
{
    public function scopeForAdminStats(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();

        return $query->where(function (Builder $q) use ($table) {
            $q->whereNull("{$table}.country")
                ->orWhereRaw('LOWER(TRIM('.$table.'.country)) != ?', ['vietnam']);
        });
    }

    public function scopeFromVietnam(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();

        return $query->whereRaw('LOWER(TRIM('.$table.'.country)) = ?', ['vietnam']);
    }

    public static function countryIsVietnam(?string $country): bool
    {
        return $country !== null && strtolower(trim($country)) === 'vietnam';
    }
}
