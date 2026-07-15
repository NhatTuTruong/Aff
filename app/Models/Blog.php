<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Support\BlogCategoryImage;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'intro_type',
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

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /** URL ảnh featured; ưu tiên featured_image, fallback ảnh danh mục hoặc mặc định. */
    public function getFeaturedImageUrlAttribute(): string
    {
        return BlogCategoryImage::resolveUrl($this->featured_image, $this->category, $this->id);
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
            if (empty($blog->featured_image)) {
                $randomPath = BlogCategoryImage::randomPathForCategory($blog->category);
                if ($randomPath !== null) {
                    $blog->featured_image = $randomPath;
                }
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

        $clearCaches = function () {
            Cache::forget('magazine.blog_nav_categories');
            Cache::forget('magazine.footer_gallery_posts');
            Cache::forget('magazine.footer_recent_posts');
        };

        static::saved($clearCaches);
        static::deleted($clearCaches);
    }
}
