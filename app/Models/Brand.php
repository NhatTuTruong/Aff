<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
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
        'category', // Keep for backward compatibility
        'events',
        'image',
        'approved',
        'short_description',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

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
                $brand->slug = "{$userCode}/{$baseSlug}";
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
            
            // Đảm bảo slug luôn có user_code prefix
            if ($brand->isDirty('slug') && !str_starts_with($brand->slug, $userCode . '/')) {
                $brand->slug = "{$userCode}/{$brand->slug}";
            }
            
            // Nếu name thay đổi và slug chưa có user_code prefix, cập nhật lại
            if ($brand->isDirty('name') && !$brand->isDirty('slug')) {
                $baseSlug = Str::slug($brand->name);
                if (!str_starts_with($brand->slug, $userCode . '/')) {
                    $brand->slug = "{$userCode}/{$baseSlug}";
                } else {
                    // Giữ user_code, chỉ cập nhật phần sau
                    $brand->slug = "{$userCode}/{$baseSlug}";
                }
            }
        });
    }
}

