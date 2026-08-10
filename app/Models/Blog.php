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
            return $this->ensureAbsoluteMediaUrl(Storage::disk('public')->url($this->featured_image));
        }
        $slug = $this->category ? Str::slug($this->category) : 'default';
        $extensions = ['webp', 'svg', 'png', 'jpg', 'jpeg', 'gif'];
        if ($slug !== 'default') {
            foreach ($extensions as $ext) {
                $path = "images/categories/{$slug}.{$ext}";
                if (file_exists(public_path($path))) {
                    return $this->ensureAbsoluteMediaUrl(asset($path));
                }
            }
        }

        $categoryDefault = public_path('category/default.webp');
        if (file_exists($categoryDefault)) {
            return $this->ensureAbsoluteMediaUrl(asset('category/default.webp'));
        }

        $legacyCategoryDefault = public_path('category/default.png');
        if (file_exists($legacyCategoryDefault)) {
            return $this->ensureAbsoluteMediaUrl(asset('category/default.png'));
        }

        foreach ($extensions as $ext) {
            $path = "images/categories/default.{$ext}";
            if (file_exists(public_path($path))) {
                return $this->ensureAbsoluteMediaUrl(asset($path));
            }
        }

        return $this->ensureAbsoluteMediaUrl(asset('images/placeholder.svg'));
    }

    /**
     * Filament ImageColumn chỉ hiển thị được src nếu là URL tuyệt đối (FILTER_VALIDATE_URL).
     * Storage::url() / asset() đôi khi trả path tương đối (/storage/...) → getImageUrl() trả null.
     */
    protected function ensureAbsoluteMediaUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return url('/images/placeholder.svg');
        }
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }
        if (str_starts_with($url, '//')) {
            $scheme = parse_url((string) config('app.url'), PHP_URL_SCHEME) ?: 'https';

            return "{$scheme}:{$url}";
        }

        return url('/'.ltrim($url, '/'));
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
