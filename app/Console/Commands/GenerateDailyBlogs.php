<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\User;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use Illuminate\Console\Command;

class GenerateDailyBlogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'blogs:generate-daily {--count=0 : Số bài cần tạo cho lần chạy này (0 = lấy theo cài đặt)} {--respect-daily-limit : Không vượt quá số bài/ngày đã cấu hình}';

    /**
     * The console command description.
     */
    protected $description = 'Tự động tạo blog ngẫu nhiên theo danh mục mặc định bằng Gemini';

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
        $variants = $this->enabledVariants();
        if ($variants === []) {
            $this->warn('Chưa bật variant nào trong Cài đặt hệ thống, bỏ qua.');

            return self::SUCCESS;
        }

        $respectDailyLimit = (bool) $this->option('respect-daily-limit');
        $count = (int) $this->option('count');
        if ($count <= 0) {
            // Khi chạy bởi scheduler với chế độ giới hạn ngày, mỗi tick tạo 1 bài
            // để phân bổ đều trong khung giờ; tổng số bài vẫn bị chặn bởi daily limit.
            $count = $respectDailyLimit ? 1 : (int) AdminSettings::get('auto_blog_daily_count', 2);
        }
        $count = max(1, $count);

        if ($respectDailyLimit) {
            $dailyLimit = max(1, (int) AdminSettings::get('auto_blog_daily_count', 2));
            $todayKey = 'auto_blog.generated.' . now()->format('Y-m-d');
            $generatedToday = (int) AdminSettings::get($todayKey, 0);
            $remaining = $dailyLimit - $generatedToday;

            if ($remaining <= 0) {
                $this->info('Đã đạt giới hạn số bài/ngày, bỏ qua.');

                return self::SUCCESS;
            }

            $count = min($count, $remaining);
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

        return self::SUCCESS;
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
     * @return array<int, string>
     */
    protected function enabledVariants(): array
    {
        $variants = [];
        if ((bool) AdminSettings::get('auto_blog_variant_best', true)) {
            $variants[] = 'best';
        }
        if ((bool) AdminSettings::get('auto_blog_variant_guide', true)) {
            $variants[] = 'guide';
        }
        if ((bool) AdminSettings::get('auto_blog_variant_comparison', true)) {
            $variants[] = 'comparison';
        }

        return $variants;
    }
}

