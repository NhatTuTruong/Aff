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
                                // Đã tạo hết blog giới thiệu store cho tất cả campaigns
                                // Chuyển sang tạo bài xoay vòng (best/guide/comparison)
                                $fallbackModes = array_filter(['best', 'guide', 'comparison'], fn ($m) => in_array($m, AutoBlogSettings::enabledManualAiModes()));
                                if (empty($fallbackModes)) {
                                    Notification::make()
                                        ->title('Không tạo được blog giới thiệu store')
                                        ->body('Không có chiến dịch phù hợp nào và không có mode xoay vòng nào được bật.')
                                        ->warning()
                                        ->send();
                                    return;
                                }
                                $mode = $fallbackModes[array_rand($fallbackModes)];
                                Notification::make()
                                    ->title('Đã tạo hết blog giới thiệu store')
                                    ->body("Chuyển sang tạo bài: {$mode}")
                                    ->info()
                                    ->send();
                            } else {
                                [$brand, $campaign] = $picked;
                                $categoryLabel = $this->resolveBrandCategoryLabel($brand);
                                $result = $gemini->generateBrandIntroBlog($brand, $campaign, $categoryLabel, $extras);
                                $campaignId = $campaign->id;
                                $introType = 'store';
                            }
                        }

                        if (!isset($result)) {
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
     * Xoay vòng qua danh sách campaigns theo thứ tự cũ -> mới.
     *
     * @return array{0: Brand, 1: Campaign}|null
     */
    protected function findNextBrandIntroCandidate(): ?array
    {
        // Lấy tất cả campaigns phù hợp (sắp xếp theo thời gian tạo: cũ -> mới)
        $allCampaigns = DB::table('campaigns')
            ->join('brands', 'campaigns.brand_id', '=', 'brands.id')
            ->whereNull('campaigns.deleted_at')
            ->whereNotNull('campaigns.affiliate_url')
            ->where('campaigns.affiliate_url', '!=', '')
            ->whereNull('brands.deleted_at')
            ->when(app()->environment('production'), fn ($q) => $q->where('campaigns.status', 'active'))
            ->orderBy('campaigns.created_at')
            ->orderBy('campaigns.id')
            ->select('campaigns.id as campaign_id', 'brands.id as brand_id')
            ->get();

        if ($allCampaigns->isEmpty()) {
            return null;
        }

        $lastIndex = (int) AdminSettings::get('auto_blog.brand_intro_last_index', -1);
        $nextIndex = ($lastIndex + 1) % $allCampaigns->count();

        $row = $allCampaigns[$nextIndex];
        $brand = Brand::query()->whereKey($row->brand_id)->first();
        $campaign = Campaign::query()->whereKey($row->campaign_id)->first();

        if ($brand && $campaign) {
            AdminSettings::set('auto_blog.brand_intro_last_index', $nextIndex);
            return [$brand, $campaign];
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
