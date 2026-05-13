<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use App\Services\BrandIntroBlogCandidateService;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use App\Support\AutoBlogSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateDailyBlogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'blogs:generate-daily {--count=0 : Số bài cần tạo cho lần chạy này (0 = lấy theo cài đặt)} {--respect-daily-limit : Không vượt quá số bài/ngày đã cấu hình}';

    /**
     * The console command description.
     */
    protected $description = 'Tự động tạo blog theo danh mục (Gemini) và tối đa 1 bài giới thiệu brand/ngày';

    /** Trọng số cho category không có cửa hàng (xác suất thấp hơn). */
    protected float $emptyCategoryWeight = 0.5;

    public function handle(GeminiBlogService $gemini): int
    {
        if (! AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'))) {
            $this->warn('Gemini API key chưa được cấu hình (Cài đặt hệ thống), bỏ qua.');
            return self::SUCCESS;
        }

        $categoryNames = config('default_categories.names', User::defaultCategoryNames());
        if (empty($categoryNames)) {
            $this->warn('Không có danh mục mặc định, bỏ qua.');
            return self::SUCCESS;
        }

        $weights = $this->buildCategoryWeights($categoryNames);
        $variants = AutoBlogSettings::enabledCategoryVariants();
        if ($variants === []) {
            $this->warn('Chưa bật variant nào — bỏ qua bài blog theo danh mục (vẫn có thể tạo blog giới thiệu brand).');
        }

        $respectDailyLimit = (bool) $this->option('respect-daily-limit');
        $count = (int) $this->option('count');
        if ($count <= 0) {
            // Khi chạy bởi scheduler với chế độ giới hạn ngày, mỗi tick tạo 1 bài
            // để phân bổ đều trong khung giờ; tổng số bài vẫn bị chặn bởi daily limit.
            $count = $respectDailyLimit ? 1 : (int) AdminSettings::get('auto_blog_daily_count', 2);
        }

        if ($variants === []) {
            $count = 0;
        } else {
            $count = max(1, $count);
            if ($respectDailyLimit) {
                $dailyLimit = max(1, (int) AdminSettings::get('auto_blog_daily_count', 2));
                $todayKey = 'auto_blog.generated.' . now()->format('Y-m-d');
                $generatedToday = (int) AdminSettings::get($todayKey, 0);
                $remaining = $dailyLimit - $generatedToday;

                if ($remaining <= 0) {
                    $this->info('Đã đạt giới hạn số bài blog theo danh mục/ngày; vẫn kiểm tra blog giới thiệu brand.');
                    $count = 0;
                } else {
                    $count = min($count, $remaining);
                }
            }
        }

        $author = User::where('is_admin', true)->first() ?? User::first();

        for ($i = 0; $i < $count; $i++) {
            $category = $this->pickWeightedCategory($weights);
            $variant = $variants[array_rand($variants)];

            $this->info("Generating blog ({$variant}) for category: {$category}");

            $result = $gemini->generateBlog($category, $variant);

            if (! $result) {
                $err = $gemini->lastError ?? 'Không rõ lỗi';
                $this->warn("Gemini lỗi, bỏ qua bài này: {$err}");
                continue;
            }

            $blog = new Blog();
            if ($author) {
                $blog->user_id = $author->id;
            }
            $blog->title = $result['title'];
            $blog->category = $category;
            $blog->content = $result['content'];
            $blog->featured_image = $result['featured_image'] ?? null;
            $blog->is_published = true;
            $blog->views_count = 0;
            $blog->save();

            if ($respectDailyLimit) {
                $todayKey = 'auto_blog.generated.' . now()->format('Y-m-d');
                $generatedToday = (int) AdminSettings::get($todayKey, 0);
                AdminSettings::set($todayKey, $generatedToday + 1);
            }

            $this->info("Đã tạo blog: {$blog->title}");
        }

        if (AutoBlogSettings::brandIntroEnabled()) {
            $this->maybeGenerateBrandIntroBlog($gemini, $categoryNames, $author);
        }

        return self::SUCCESS;
    }

    /**
     * Tối đa 1 bài "brand intro" mỗi ngày (độc lập số bài category khác).
     */
    protected function maybeGenerateBrandIntroBlog(GeminiBlogService $gemini, array $categoryNames, ?User $author): void
    {
        $day = now()->format('Y-m-d');
        if ((int) AdminSettings::get("auto_blog.intro_done.{$day}", 0) >= 1) {
            return;
        }

        $picked = app(BrandIntroBlogCandidateService::class)->findBrandAndCampaignForIntro();
        if ($picked === null) {
            $this->warn('Blog giới thiệu store: không có chiến dịch nào có affiliate_url phù hợp.');

            return;
        }

        /** @var Brand $brand */
        /** @var Campaign $campaign */
        [$brand, $campaign] = $picked;
        $categoryLabel = $this->resolveBrandCategoryLabel($brand);

        $this->info("Generating brand intro blog for store: {$brand->name} (campaign #{$campaign->id})");

        $result = $gemini->generateBrandIntroBlog($brand, $campaign, $categoryLabel);
        if (! $result) {
            $err = $gemini->lastError ?? 'Không rõ lỗi';
            $this->warn("Gemini lỗi (blog giới thiệu brand), sẽ thử lại lần chạy sau: {$err}");

            return;
        }

        $blog = new Blog();
        if ($author) {
            $blog->user_id = $author->id;
        }
        $blog->title = $result['title'];
        $blog->category = $categoryLabel;
        $blog->content = $result['content'];
        $blog->featured_image = $result['featured_image'] ?? null;
        $blog->is_published = true;
        $blog->views_count = 0;
        $blog->save();

        AdminSettings::set("auto_blog.intro_done.{$day}", 1);
        $this->info("Đã tạo blog giới thiệu brand: {$blog->title}");
    }

    /** Đếm số cửa hàng theo tên category, ghép với default categories. */
    protected function buildCategoryWeights(array $categoryNames): array
    {
        $counts = Brand::query()
            ->join('categories', 'brands.category_id', '=', 'categories.id')
            ->whereNull('categories.deleted_at')
            ->selectRaw('categories.name as name, count(*) as cnt')
            ->groupBy('categories.name')
            ->pluck('cnt', 'name')
            ->toArray();

        $weights = [];
        foreach ($categoryNames as $name) {
            $cnt = (int) ($counts[$name] ?? 0);
            $weights[$name] = $cnt > 0 ? $cnt : $this->emptyCategoryWeight;
        }

        return $weights;
    }

    protected function pickWeightedCategory(array $weights): string
    {
        $total = array_sum($weights);
        if ($total <= 0) {
            return array_key_first($weights) ?? 'Tech';
        }
        $r = mt_rand(1, 10000) / 10000 * $total;
        $cum = 0.0;
        foreach ($weights as $name => $w) {
            $cum += $w;
            if ($r <= $cum) {
                return $name;
            }
        }

        return array_key_last($weights) ?? 'Tech';
    }

    /**
     * Ưu tiên danh mục thật của brand, hỗ trợ cả dữ liệu legacy.
     */
    protected function resolveBrandCategoryLabel(Brand $brand): string
    {
        $brand->loadMissing('category');

        if (filled($brand->category?->name)) {
            return (string) $brand->category->name;
        }

        // Category có thể đã soft-delete nên relation mặc định không lấy ra.
        if ($brand->category_id) {
            $category = Category::withTrashed()->find($brand->category_id);
            if (filled($category?->name)) {
                return (string) $category->name;
            }
        }

        $legacyCategory = trim((string) $brand->getAttribute('category'));
        if ($legacyCategory !== '') {
            // Legacy có thể lưu dạng "00000/fashion" -> chuyển về "Fashion".
            $raw = (string) Str::of($legacyCategory)->afterLast('/')->replace('-', ' ')->replace('_', ' ');
            $normalized = Str::title(trim($raw));
            if ($normalized !== '') {
                return $normalized;
            }
        }

        return (string) (config('default_categories.names.0') ?? 'General');
    }

}

