<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Support\BlogCategoryImage;
use App\Support\BlogContentHtml;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'affiliate_url',
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
        'created_at',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'created_at' => 'datetime',
    ];

    /** Nội dung đã loại tên file / dung lượng dưới ảnh đính kèm. */
    public function getRenderedContentAttribute(): string
    {
        $html = BlogContentHtml::stripAttachmentCaptions($this->content);

        $gemini = app(\App\Services\GeminiBlogService::class);

        if (filled($this->campaign_id)) {
            $campaign = $this->relationLoaded('campaign')
                ? $this->campaign
                : $this->campaign()->with('couponItems')->first();

            if ($campaign) {
                $html = $gemini->prepareStoreBlogHtml($html, $campaign);
            }
        } elseif (filled($this->affiliate_url)) {
            $html = $gemini->prepareAffiliateBlogHtml($html, (string) $this->affiliate_url);
        } else {
            $html = app(\App\Services\BlogApifyImageService::class)->redistributeContentImages($html);
        }

        return $html;
    }

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

    /** Link affiliate tracking cho CTA / ảnh trong bài (từ campaign hoặc cột affiliate_url). */
    public function getAffiliateTrackingUrlAttribute(): ?string
    {
        if (filled($this->affiliate_url)) {
            return (string) $this->affiliate_url;
        }

        if (! filled($this->campaign_id)) {
            return null;
        }

        $campaign = $this->relationLoaded('campaign')
            ? $this->campaign
            : $this->campaign()->first();

        if (! $campaign) {
            return null;
        }

        return route('click.redirect', ['slug' => $campaign->slug], true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Blog $blog) {
            if (filled($blog->content)) {
                $blog->content = BlogContentHtml::stripAttachmentCaptions($blog->content);
            }

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
            if ($blog->isDirty('content')) {
                $blog->content = BlogContentHtml::stripAttachmentCaptions($blog->content);
            }

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
