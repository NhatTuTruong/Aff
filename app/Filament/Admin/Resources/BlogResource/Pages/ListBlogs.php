<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Services\BrandIntroBlogCandidateService;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use App\Support\AutoBlogSettings;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
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
                ->requiresConfirmation()
                ->modalHeading('Tạo bài viết bằng AI')
                ->modalDescription(function (): string {
                    $modes = AutoBlogSettings::enabledManualAiModes();
                    if ($modes === []) {
                        return 'Hiện không có loại bài nào được bật trong Cài đặt hệ thống → Auto Blog. Bật ít nhất một tùy chọn (giới thiệu store hoặc variant best/guide/comparison).';
                    }
                    $labels = array_map(fn (string $m): string => match ($m) {
                        'intro' => 'blog giới thiệu store (chiến dịch import)',
                        'best' => 'best / deals theo danh mục',
                        'guide' => 'hướng dẫn mua theo danh mục',
                        'comparison' => 'so sánh theo danh mục',
                        default => $m,
                    }, $modes);

                    return 'AI sẽ chọn ngẫu nhiên một trong các dạng đang bật trong Cài đặt hệ thống: '.implode(', ', $labels)
                        .'. Quá trình có thể mất 30–90 giây.';
                })
                ->modalSubmitActionLabel('Tạo ngay')
                ->action(function (): void {
                    $gemini = app(GeminiBlogService::class);

                    if (! AdminSettings::getEncrypted('gemini_api_key', (string) config('gemini.api_key'))) {
                        Notification::make()
                            ->title('Lỗi cấu hình')
                            ->body('Gemini API key chưa được cấu hình trong Cài đặt hệ thống.')
                            ->danger()
                            ->send();
                        return;
                    }

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
                        $picked = app(BrandIntroBlogCandidateService::class)->findBrandAndCampaignForIntro();
                        if ($picked === null) {
                            Notification::make()
                                ->title('Không tạo được blog giới thiệu store')
                                ->body('Cần ít nhất một chiến dịch có affiliate_url hợp lệ (và chưa bị xóa).')
                                ->danger()
                                ->send();
                            return;
                        }
                        [$brand, $campaign] = $picked;
                        $categoryLabel = $this->resolveBrandCategoryLabel($brand);
                        $result = $gemini->generateBrandIntroBlog($brand, $campaign, $categoryLabel);
                    } else {
                        $category = $categoryNames[array_rand($categoryNames)];
                        $result = $gemini->generateBlog($category, $mode);
                        $categoryLabel = $category;
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
                        'title' => $result['title'],
                        'category' => $categoryLabel,
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
