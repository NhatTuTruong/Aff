<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use App\Support\AutoBlogSettings;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Quay lại')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(url()->previous()),
            Actions\Action::make('createWithAi')
                ->label('Tạo bài viết bằng AI')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->modalHeading('Tạo bài viết bằng AI')
                ->modalDescription(function (): string {
                    $modes = AutoBlogSettings::enabledManualAiModes();
                    $spotlight = 'Nếu nhập tên hoặc domain bất kỳ: AI viết bài ngắn tiếng Anh về subject đó — không cần brand hay campaign có trên hệ thống.';
                    if ($modes === []) {
                        return $spotlight.' Nếu để trống ô đó, cần bật ít nhất một loại bài trong Cài đặt hệ thống → Auto Blog (best / guide / comparison / intro). Quá trình có thể mất 30–90 giây.';
                    }
                    $labels = array_map(fn (string $m): string => match ($m) {
                        'intro' => 'blog giới thiệu store',
                        'best' => 'best / deals theo danh mục',
                        'guide' => 'hướng dẫn mua theo danh mục',
                        'comparison' => 'so sánh theo danh mục',
                        default => $m,
                    }, $modes);

                    return $spotlight.' Nếu để trống ô đó: AI chọn ngẫu nhiên một trong các dạng đang bật: '.implode(', ', $labels)
                        .'. Quá trình có thể mất 30–90 giây.';
                })
                ->modalSubmitActionLabel('Tạo ngay')
                ->form([
                    Select::make('category_id')
                        ->label('Danh mục')
                        ->placeholder('Tự động')
                        ->options(fn (): array => Category::query()->orderBy('name')->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->preload(),
                    TextInput::make('brand_or_domain')
                        ->label('Nhập brand hay domain')
                        ->placeholder('VD: Barbell Jack, barbelljack.com')
                        ->helperText('Để trống: theo logic ngẫu nhiên.')
                        ->maxLength(255),
                    Textarea::make('idea')
                        ->label('Ý tưởng / yêu cầu bài viết')
                        ->placeholder('VD: Viết theo phong cách review ngắn, nhấn mạnh shipping/return, thêm FAQ... (có thể dán outline hoặc gạch đầu dòng)')
                        ->rows(5)
                        ->maxLength(2000),
                    TextInput::make('affiliate_url')
                        ->label('Link Affiliate (tuỳ chọn)')
                        ->placeholder('https://...')
                        ->url()
                        ->maxLength(2048)
                        ->helperText('Nếu nhập, AI sẽ chèn CTA/link trong bài viết.'),
                    TextInput::make('coupon_code')
                        ->label('Mã coupon (tuỳ chọn)')
                        ->placeholder('VD: SAVE10')
                        ->maxLength(80)
                        ->helperText('Nếu nhập, AI sẽ hiển thị mã trong bài viết (không tự bịa thêm điều kiện).'),
                ])
                ->action(function (array $data): void {
                    $gemini = app(GeminiBlogService::class);

                    if (! AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'))) {
                        Notification::make()
                            ->title('Lỗi cấu hình')
                            ->body('Gemini API key chưa được cấu hình trong Cài đặt hệ thống.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $brandHint = trim((string) ($data['brand_or_domain'] ?? ''));
                    $extras = [
                        'idea' => trim((string) ($data['idea'] ?? '')),
                        'affiliate_url' => trim((string) ($data['affiliate_url'] ?? '')),
                        'coupon_code' => trim((string) ($data['coupon_code'] ?? '')),
                    ];
                    $pickedCategoryId = $data['category_id'] ?? null;
                    $pickedCategoryName = null;
                    if (! empty($pickedCategoryId)) {
                        $pickedCategoryName = Category::query()->whereKey($pickedCategoryId)->value('name');
                        $pickedCategoryName = $pickedCategoryName ? trim((string) $pickedCategoryName) : null;
                    }

                    if ($brandHint !== '') {
                        $categoryLabel = 'Brand spotlight';
                        $result = $gemini->generateBrandSpotlightFromHint($brandHint, $extras);
                        $campaignId = null;
                        $introType = null;
                    } else {
                        $modes = AutoBlogSettings::enabledManualAiModes();
                        if ($modes === []) {
                            Notification::make()
                                ->title('Chưa bật loại bài AI')
                                ->body('Vào Cài đặt hệ thống → Auto Blog và bật ít nhất một tùy chọn (giới thiệu store hoặc variant best / guide / comparison).')
                                ->warning()
                                ->send();
                            return;
                        }

                        $mode = $modes[array_rand($modes)];

                        $categoryNames = config('default_categories.names', User::defaultCategoryNames());
                        if ($mode !== 'intro' && (empty($categoryNames) || ! is_array($categoryNames))) {
                            Notification::make()
                                ->title('Lỗi')
                                ->body('Không có danh mục mặc định cho bài theo danh mục.')
                                ->danger()
                                ->send();
                            return;
                        }

                        if ($mode === 'intro') {
                            $picked = $this->findNextBrandIntroCandidate();
                            if ($picked === null) {
                                // Reset vòng lặp: xóa trạng thái last_brand_id và thử lại
                                AdminSettings::forget('auto_blog.brand_intro_last_brand_id');
                                $picked = $this->findNextBrandIntroCandidate();
                                if ($picked === null) {
                                    Notification::make()
                                        ->title('Không tạo được blog giới thiệu store')
                                        ->body('Không có chiến dịch phù hợp nào.')
                                        ->warning()
                                        ->send();
                                    return;
                                }
                            }
                            [$brand, $campaign] = $picked;
                            $categoryLabel = $this->resolveBrandCategoryLabel($brand);
                            $result = $gemini->generateBrandIntroBlog($brand, $campaign, $categoryLabel, $extras);
                            $campaignId = $campaign->id;
                            $introType = 'store';
                        } else {
                            $category = $categoryNames[array_rand($categoryNames)];
                            $result = $gemini->generateBlog($category, $mode, $extras);
                            $categoryLabel = $category;
                            $campaignId = null;
                            $introType = $mode;
                        }
                    }

                    // Override category only for classification, not for AI prompt.
                    if (filled($pickedCategoryName)) {
                        $categoryLabel = $pickedCategoryName;
                    }

                    if (! $result) {
                        Notification::make()
                            ->title('Lỗi AI')
                            ->body($gemini->lastError ?? 'Không thể tạo nội dung từ AI.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $author = User::where('is_admin', true)->first() ?? User::first();

                    $blog = Blog::create([
                        'user_id' => $author?->id,
                        'campaign_id' => $campaignId ?? null,
                        'intro_type' => $introType ?? null,
                        'title' => $result['title'],
                        'category' => $categoryLabel ?? 'General',
                        'content' => $result['content'],
                        'featured_image' => $result['featured_image'] ?? null,
                        'is_published' => true,
                        'views_count' => 0,
                    ]);

                    Notification::make()
                        ->title('Đã tạo bài viết')
                        ->body("Bài viết \"{$blog->title}\" đã được tạo thành công.")
                        ->success()
                        ->send();

                    $this->redirect(BlogResource::getUrl('edit', ['record' => $blog]));
                }),
            Actions\CreateAction::make()
                ->label('Thêm blog'),
        ];
    }

    /**
     * Tìm brand + campaign tiếp theo cho blog giới thiệu store.
     * Không tạo trùng campaign đã có blog.
     *
     * @return array{0: Brand, 1: Campaign}|null
     */
    protected function findNextBrandIntroCandidate(): ?array
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

        // Tìm brand tiếp theo có campaign chưa có blog
        $maxAttempts = $brandRows->count();
        $startIndex = 0;
        if ($lastBrandId !== null) {
            foreach ($brandRows as $i => $row) {
                if ((int) $row->id === (int) $lastBrandId) {
                    $startIndex = ($i + 1) % $brandRows->count();
                    break;
                }
            }
        }

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $nextIndex = ($startIndex + $attempt) % $brandRows->count();
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

    protected function resolveBrandCategoryLabel(\App\Models\Brand $brand): string
    {
        $brand->loadMissing('category');

        if (filled($brand->category?->name)) {
            return (string) $brand->category->name;
        }

        // Category có thể đã soft-delete nên relation mặc định trả null.
        if ($brand->category_id) {
            $category = Category::withTrashed()->find($brand->category_id);
            if (filled($category?->name)) {
                return (string) $category->name;
            }
        }

        $legacyCategory = trim((string) $brand->getAttribute('category'));
        if ($legacyCategory !== '') {
            $raw = (string) Str::of($legacyCategory)->afterLast('/')->replace('-', ' ')->replace('_', ' ');
            $normalized = Str::title(trim($raw));
            if ($normalized !== '') {
                return $normalized;
            }
        }

        return (string) (config('default_categories.names.0') ?? 'General');
    }
}
