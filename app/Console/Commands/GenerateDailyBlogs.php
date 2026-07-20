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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateDailyBlogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'blogs:generate-daily {--count=1 : Số bài cần tạo cho lần chạy này}';

    /**
     * The console command description.
     */
    protected $description = 'Tự động tạo blog theo danh mục (Gemini) và tối đa 1 bài giới thiệu brand/ngày';

    /** Trọng số cho category không có cửa hàng (xác suất thấp hơn). */
    protected float $emptyCategoryWeight = 0.5;

    public function handle(GeminiBlogService $gemini): int
    {
        if (AdminSettings::geminiApiKeys() === []) {
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

        $count = (int) $this->option('count');
        if ($variants !== []) {
            $count = max(1, $count);
        } else {
            $count = 0;
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
        $intervalHours = AutoBlogSettings::brandIntroIntervalHours();
        $lastGeneratedAt = AdminSettings::get('auto_blog.brand_intro_last_generated_at');

        if ($lastGeneratedAt !== null) {
            $lastGenerated = \Carbon\Carbon::parse($lastGeneratedAt);
            $nextAllowed = $lastGenerated->copy()->addHours($intervalHours);

            if (now()->lessThan($nextAllowed)) {
                $this->info('Bỏ qua blog giới thiệu brand: chưa hết khoảng cách đăng bài ('.($nextAllowed->diffInMinutes(now())).' phút nữa).');

                return;
            }
        }

        $candidate = $this->pickNextBrandIntroCandidate();
        if ($candidate === null) {
            $this->warn('Blog giới thiệu store: không có chiến dịch nào có affiliate_url phù hợp.');

            return;
        }

        [$brand, $campaign] = $candidate;
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
        $blog->campaign_id = $campaign->id;
        $blog->intro_type = 'store';
        $blog->title = $result['title'];
        $blog->category = $categoryLabel;
        $blog->content = $result['content'];
        $blog->featured_image = $result['featured_image'] ?? null;
        $blog->is_published = true;
        $blog->views_count = 0;
        $blog->save();

        AdminSettings::set('auto_blog.brand_intro_last_generated_at', now()->toDateTimeString());
        $this->info("Đã tạo blog giới thiệu brand: {$blog->title}");
    }

    /**
     * Chọn brand + campaign tiếp theo theo vòng lặp:
     * - Mỗi brand chỉ được chọn khi có ít nhất 1 campaign có affiliate_url
     * - Campaign được chọn là campaign cũ nhất (created_at ASC) của brand đó
     * - Xoay vòng qua các brand theo thứ tự đã sắp xếp để mỗi lần đăng là 1 brand khác nhau
     * - Bỏ qua campaign đã có blog giới thiệu (trừ khi blog đã bị xóa)
     *
     * @return array{0: Brand, 1: Campaign}|null
     */
    protected function pickNextBrandIntroCandidate(): ?array
    {
        // Lấy danh sách campaign_id đã có blog giới thiệu (chưa xóa)
        $usedCampaignIds = Blog::whereNotNull('campaign_id')
            ->where('intro_type', 'store')
            ->pluck('campaign_id')
            ->toArray();

        $brandRows = DB::table('brands')
            ->join('campaigns', 'campaigns.brand_id', '=', 'brands.id')
            ->whereNull('campaigns.deleted_at')
            ->whereNotNull('campaigns.affiliate_url')
            ->where('campaigns.affiliate_url', '!=', '')
            ->whereNull('brands.deleted_at')
            ->when(app()->environment('production'), fn ($q) => $q->where('campaigns.status', 'active'))
            ->select('brands.id', 'brands.name', DB::raw('MIN(campaigns.created_at) as oldest_campaign_at'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('oldest_campaign_at')
            ->get();

        if ($brandRows->isEmpty()) {
            return null;
        }

        // Lọc bỏ brand mà tất cả campaign đã có blog
        $availableBrandIds = [];
        foreach ($brandRows as $row) {
            $brandCampaigns = DB::table('campaigns')
                ->where('brand_id', $row->id)
                ->whereNull('deleted_at')
                ->whereNotNull('affiliate_url')
                ->where('affiliate_url', '!=', '')
                ->when(app()->environment('production'), fn ($q) => $q->where('status', 'active'))
                ->pluck('id')
                ->toArray();

            $hasAvailableCampaign = false;
            foreach ($brandCampaigns as $cid) {
                if (!in_array($cid, $usedCampaignIds)) {
                    $hasAvailableCampaign = true;
                    break;
                }
            }

            if ($hasAvailableCampaign) {
                $availableBrandIds[] = (int) $row->id;
            }
        }

        // Nếu đã đăng hết tất cả campaigns, reset và bắt đầu lại từ đầu
        if (empty($availableBrandIds)) {
            $availableBrandIds = $brandRows->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        }

        $lastBrandId = AdminSettings::get('auto_blog.brand_intro_last_brand_id');

        $index = 0;
        if ($lastBrandId !== null) {
            $found = false;
            foreach ($brandRows as $i => $row) {
                if ((int) $row->id === (int) $lastBrandId) {
                    $index = ($i + 1) % $brandRows->count();
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $index = 0;
            }
        }

        // Tìm brand tiếp theo có campaign chưa có blog
        $maxAttempts = $brandRows->count();
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $nextIndex = ($index + $attempt) % $brandRows->count();
            $candidateBrandId = (int) $brandRows[$nextIndex]->id;

            if (!in_array($candidateBrandId, $availableBrandIds)) {
                continue;
            }

            $brand = Brand::query()->whereKey($candidateBrandId)->first();
            if (!$brand) {
                continue;
            }

            // Tìm campaign cũ nhất của brand chưa có blog
            $campaign = Campaign::query()
                ->where('campaigns.brand_id', $brand->id)
                ->whereNull('campaigns.deleted_at')
                ->whereNotNull('campaigns.affiliate_url')
                ->where('campaigns.affiliate_url', '!=', '')
                ->when(app()->environment('production'), fn ($q) => $q->where('campaigns.status', 'active'))
                ->whereNotIn('campaigns.id', $usedCampaignIds)
                ->orderBy('campaigns.created_at')
                ->orderBy('campaigns.id')
                ->first();

            if ($campaign) {
                AdminSettings::set('auto_blog.brand_intro_last_brand_id', $brand->id);
                return [$brand, $campaign];
            }
        }

        return null;
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

