<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\User;
use App\Services\GeminiBlogService;
use Illuminate\Console\Command;

class GenerateDailyBlogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'blogs:generate-daily {--count=1 : Số bài muốn tạo mỗi ngày}';

    /**
     * The console command description.
     */
    protected $description = 'Tự động tạo blog ngẫu nhiên theo danh mục mặc định bằng Gemini';

    /** Trọng số cho category không có cửa hàng (xác suất thấp hơn). */
    protected float $emptyCategoryWeight = 0.5;

    public function handle(GeminiBlogService $gemini): int
    {
        if (! config('gemini.api_key')) {
            $this->warn('GEMINI_API_KEY chưa được cấu hình, bỏ qua.');
            return self::SUCCESS;
        }

        $categoryNames = config('default_categories.names', User::defaultCategoryNames());
        if (empty($categoryNames)) {
            $this->warn('Không có danh mục mặc định, bỏ qua.');
            return self::SUCCESS;
        }

        $weights = $this->buildCategoryWeights($categoryNames);
        $variants = ['best', 'guide', 'comparison'];
        $count = (int) $this->option('count');
        $count = $count > 0 ? $count : 1;

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
}

