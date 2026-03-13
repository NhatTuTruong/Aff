<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'slug',
        'content',
        'featured_image',
        'images',
        'videos',
        'is_published',
        'views_count',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** URL ảnh featured; ưu tiên featured_image, fallback ảnh mặc định theo category. */
    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
            return Storage::disk('public')->url($this->featured_image);
        }
        $slug = $this->category ? Str::slug($this->category) : 'default';
        $basePath = public_path('images/categories');
        $extensions = ['svg', 'png', 'jpg', 'jpeg', 'webp', 'gif'];
        if ($slug !== 'default') {
            foreach ($extensions as $ext) {
                $path = "images/categories/{$slug}.{$ext}";
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }
        }
        foreach ($extensions as $ext) {
            $path = "images/categories/default.{$ext}";
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return asset('images/placeholder.svg');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $baseSlug = Str::slug($blog->title);
                $slug = $baseSlug;
                $n = 0;
                while (static::where('slug', $slug)->exists()) {
                    $n++;
                    $slug = $baseSlug . '-' . $n;
                }
                $blog->slug = $slug;
            }
            if (empty($blog->user_id) && auth()->check()) {
                $blog->user_id = auth()->id();
            }
        });

        static::updating(function (Blog $blog) {
            if ($blog->isDirty('title') && ! $blog->isDirty('slug')) {
                $baseSlug = Str::slug($blog->title);
                $slug = $baseSlug;
                $n = 0;
                while (static::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                    $n++;
                    $slug = $baseSlug . '-' . $n;
                }
                $blog->slug = $slug;
            }
        });
    }
}
