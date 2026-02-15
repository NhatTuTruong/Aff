<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

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
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
}
