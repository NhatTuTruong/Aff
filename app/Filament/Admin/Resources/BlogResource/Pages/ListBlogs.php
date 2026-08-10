<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Services\BlogAiContentEnricher;
use App\Services\BrandIntroBlogCandidateService;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use App\Support\AutoBlogSettings;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

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
                    $base = 'Danh mục chỉ để phân loại bài trên site (mặc định: general), không ảnh hưởng prompt AI. Link aff và mã coupon (nếu có) sẽ được chèn vào cuối bài.';
                    $spotlight = 'Nếu nhập brand/domain: AI viết bài ngắn tiếng Anh về subject đó.';
                    if ($modes === []) {
                        return $base.' '.$spotlight.' Nếu để trống brand/domain, cần bật ít nhất một loại bài trong Cài đặt hệ thống → Auto Blog. Quá trình có thể mất 30–90 giây.';
                    }
                    $labels = array_map(fn (string $m): string => match ($m) {
                        'intro' => 'blog giới thiệu store',
                        'best' => 'best / deals theo danh mục',
                        'guide' => 'hướng dẫn mua theo danh mục',
                        'comparison' => 'so sánh theo danh mục',
                        default => $m,
                    }, $modes);

                    return $base.' '.$spotlight.' Nếu để trống brand/domain: theo Auto Blog — lần lượt từng chiến dịch (cũ → mới, xoay vòng): '.implode(', ', $labels)
                        .'. Quá trình có thể mất 30–90 giây.';
                })
                ->modalSubmitActionLabel('Tạo ngay')
                ->form([
                    Select::make('post_category')
                        ->label('Danh mục')
                        ->options(fn (): array => Category::query()
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name', 'name')
                            ->all())
                        ->placeholder('Chọn danh mục (tùy chọn)')
                        ->searchable()
                        ->helperText('Tùy chọn — lấy từ Quản lý danh mục. Để trống sẽ dùng general. Không ảnh hưởng nội dung AI.'),
                    TextInput::make('brand_or_domain')
                        ->label('Nhập brand hay domain')
                        ->placeholder('VD: Barbell Jack, barbelljack.com')
                        ->helperText('Để trống: theo luồng Auto Blog trong Cài đặt hệ thống.')
                        ->maxLength(255),
                    TextInput::make('affiliate_link')
                        ->label('Link aff')
                        ->url()
                        ->placeholder('https://...')
                        ->helperText('Tùy chọn. Hiển thị trong nội dung bài viết.')
                        ->maxLength(2048),
                    Textarea::make('coupon_codes')
                        ->label('Mã coupon')
                        ->placeholder("SAVE10\nFREESHIP")
                        ->helperText('Tùy chọn. Mỗi dòng một mã (hoặc phân cách bằng dấu phẩy). Hiển thị trong bài viết.')
                        ->rows(4),
                ])
                ->action(function (array $data): void {
                    $gemini = app(GeminiBlogService::class);
                    $enricher = app(BlogAiContentEnricher::class);

                    if (! AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'))) {
                        Notification::make()
                            ->title('Lỗi cấu hình')
                            ->body('Gemini API key chưa được cấu hình trong Cài đặt hệ thống.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $postCategory = trim((string) ($data['post_category'] ?? ''));
                    $affiliateLink = trim((string) ($data['affiliate_link'] ?? ''));
                    $couponCodes = $enricher->parseCouponCodes($data['coupon_codes'] ?? null);
                    $brandHint = trim((string) ($data['brand_or_domain'] ?? ''));
                    $introCandidate = app(BrandIntroBlogCandidateService::class);
                    $introBrand = null;
                    $introCampaign = null;

                    if ($brandHint !== '') {
                        $picked = $introCandidate->findBrandAndCampaignByHint($brandHint);
                        if ($picked !== null) {
                            [$introBrand, $introCampaign] = $picked;
                            $aiCategory = $introCandidate->resolveBrandCategoryLabel($introBrand);
                            $result = $gemini->generateBrandIntroBlog($introBrand, $introCampaign, $aiCategory);
                        } else {
                            $result = $gemini->generateBrandSpotlightFromHint($brandHint);
                        }
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
                            $picked = $introCandidate->peekNextForIntroQueue();
                            if ($picked === null) {
                                Notification::make()
                                    ->title('Không tạo được blog giới thiệu store')
                                    ->body('Không có chiến dịch nào trên hệ thống.')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            [$introBrand, $introCampaign] = $picked;
                            $aiCategory = $introCandidate->resolveBrandCategoryLabel($introBrand);
                            $result = $gemini->generateBrandIntroBlog($introBrand, $introCampaign, $aiCategory);
                        } else {
                            $aiCategory = $categoryNames[array_rand($categoryNames)];
                            $result = $gemini->generateBlog($aiCategory, $mode);
                        }
                    }

                    if (! $result) {
                        Notification::make()
                            ->title('Lỗi AI')
                            ->body($gemini->lastError ?? 'Không thể tạo nội dung từ AI.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $content = (string) ($result['content'] ?? '');

                    if ($introCampaign !== null) {
                        $content = $enricher->appendCampaignDeals($content, $introCampaign);
                    } elseif ($affiliateLink !== '' || $couponCodes !== []) {
                        $content = $enricher->appendDealsBlock(
                            $content,
                            $affiliateLink !== '' ? $affiliateLink : null,
                            $couponCodes
                        );
                    }

                    if ($introBrand !== null) {
                        $blogCategory = $postCategory !== ''
                            ? $postCategory
                            : $introCandidate->resolveBrandCategoryLabel($introBrand);
                    } else {
                        $blogCategory = $postCategory !== '' ? $postCategory : 'general';
                    }

                    $author = User::where('is_admin', true)->first() ?? User::first();

                    $blog = Blog::create([
                        'user_id' => $author?->id,
                        'title' => $result['title'],
                        'category' => $blogCategory,
                        'content' => $content,
                        'featured_image' => $result['featured_image'] ?? null,
                        'is_published' => true,
                        'views_count' => 0,
                    ]);

                    Notification::make()
                        ->title('Đã tạo bài viết')
                        ->body("Bài viết \"{$blog->title}\" đã được tạo thành công.")
                        ->success()
                        ->send();

                    if ($introCampaign !== null && $brandHint === '') {
                        $introCandidate->advanceIntroQueue();
                    }

                    $this->redirect(BlogResource::getUrl('edit', ['record' => $blog]));
                }),
            Actions\CreateAction::make()
                ->label('Thêm blog'),
        ];
    }
}
