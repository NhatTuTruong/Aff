<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'import_id',
        'category_id',
        'name',
        'slug',
        'domain',
        'category', // Keep for backward compatibility
        'events',
        'image',
        'approved',
        'short_description',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    /** URL ảnh đại diện; dùng default nếu chưa có ảnh. */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        return asset('images/default-brand.svg');
    }

    /** Chuẩn hóa path: form/Livewire có thể gửi array [uuid => path] hoặc [0 => path], DB lưu string. */
    public function setImageAttribute($value): void
    {
        if (is_array($value)) {
            $value = reset($value);
        }
        $this->attributes['image'] = is_string($value) ? str_replace('\\', '/', ltrim((string) $value, '/')) : $value;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Tạo slug không trùng cho Brand.
     */
    public static function ensureUniqueBrandSlug(string $base, ?int $ignoreBrandId = null): string
    {
        $base = Str::slug($base) ?: 'brand';
        $candidate = $base;
        $n = 2;

        $conflicts = static::query()
            ->where('slug', $candidate)
            ->whereNull('deleted_at')
            ->when($ignoreBrandId !== null, fn ($q) => $q->where('id', '!=', $ignoreBrandId));

        while ($conflicts->exists()) {
            $candidate = $base . '-' . $n;
            $n++;
            $conflicts = static::query()
                ->where('slug', $candidate)
                ->whereNull('deleted_at')
                ->when($ignoreBrandId !== null, fn ($q) => $q->where('id', '!=', $ignoreBrandId));
        }

        return $candidate;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->user_id)) {
                $brand->user_id = Auth::id();
            }

            // Lấy user_code an toàn, tránh lỗi khi user chưa có code
            $userCode = '00000';
            if ($brand->user_id) {
                try {
                    $user = \App\Models\User::withoutEvents(function () use ($brand) {
                        return \App\Models\User::find($brand->user_id);
                    });
                    $userCode = $user?->code ?? '00000';
                } catch (\Exception $e) {
                    // Nếu có lỗi, dùng default code
                    $userCode = '00000';
                }
            }

            if (empty($brand->slug)) {
                $baseSlug = Str::slug($brand->name);
                $slug = static::ensureUniqueBrandSlug($baseSlug);
                $brand->slug = "{$userCode}/{$slug}";
            } elseif (!str_starts_with($brand->slug, $userCode . '/')) {
                // Nếu slug đã có nhưng không có user_code prefix, thêm vào
                $brand->slug = "{$userCode}/{$brand->slug}";
            }
        });

        static::updating(function ($brand) {
            // Lấy user_code an toàn
            $userCode = '00000';
            if ($brand->user_id) {
                try {
                    $user = \App\Models\User::withoutEvents(function () use ($brand) {
                        return \App\Models\User::find($brand->user_id);
                    });
                    $userCode = $user?->code ?? '00000';
                } catch (\Exception $e) {
                    $userCode = '00000';
                }
            }

            // Nếu slug thay đổi, đảm bảo không trùng
            if ($brand->isDirty('slug')) {
                $slugParts = array_values(array_filter(explode('/', $brand->slug)));
                $lastPart = end($slugParts) ?: Str::slug($brand->name) ?: 'brand';
                $uniqueSlug = static::ensureUniqueBrandSlug($lastPart, $brand->id);
                $brand->slug = str_contains($brand->slug, '/')
                    ? "{$userCode}/{$uniqueSlug}"
                    : "{$userCode}/{$uniqueSlug}";
            }

            // Nếu name thay đổi và slug chưa có user_code prefix, cập nhật lại
            if ($brand->isDirty('name') && !$brand->isDirty('slug')) {
                $baseSlug = Str::slug($brand->name);
                $slugParts = array_values(array_filter(explode('/', $brand->slug)));
                $lastPart = end($slugParts) ?: $baseSlug;
                $uniqueSlug = static::ensureUniqueBrandSlug($lastPart, $brand->id);
                $brand->slug = str_contains($brand->slug, '/')
                    ? "{$userCode}/{$uniqueSlug}"
                    : "{$userCode}/{$uniqueSlug}";
            }
        });
    }
}

