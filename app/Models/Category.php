<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'import_id',
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->user_id)) {
                $category->user_id = Auth::id();
            }
            
            // Lấy user_code an toàn
            $userCode = '00000';
            if ($category->user_id) {
                try {
                    $user = \App\Models\User::withoutEvents(function () use ($category) {
                        return \App\Models\User::find($category->user_id);
                    });
                    $userCode = $user?->code ?? '00000';
                } catch (\Exception $e) {
                    $userCode = '00000';
                }
            }
            
            if (empty($category->slug)) {
                $baseSlug = Str::slug($category->name);
                $category->slug = "{$userCode}/{$baseSlug}";
            } elseif (!str_starts_with($category->slug, $userCode . '/')) {
                // Nếu slug đã có nhưng không có user_code prefix, thêm vào
                $category->slug = "{$userCode}/{$category->slug}";
            }
        });

        static::updating(function ($category) {
            // Lấy user_code an toàn
            $userCode = '00000';
            if ($category->user_id) {
                try {
                    $user = \App\Models\User::withoutEvents(function () use ($category) {
                        return \App\Models\User::find($category->user_id);
                    });
                    $userCode = $user?->code ?? '00000';
                } catch (\Exception $e) {
                    $userCode = '00000';
                }
            }
            
            // Đảm bảo slug luôn có user_code prefix
            if ($category->isDirty('slug') && !str_starts_with($category->slug, $userCode . '/')) {
                $category->slug = "{$userCode}/{$category->slug}";
            }
            
            // Nếu name thay đổi và slug chưa có user_code prefix, cập nhật lại
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $baseSlug = Str::slug($category->name);
                if (!str_starts_with($category->slug, $userCode . '/')) {
                    $category->slug = "{$userCode}/{$baseSlug}";
                } else {
                    // Giữ user_code, chỉ cập nhật phần sau
                    $category->slug = "{$userCode}/{$baseSlug}";
                }
            }
        });
    }
}
