<?php

namespace App\Filament\Admin\Resources\BlogResource\Pages;

use App\Filament\Admin\Resources\BlogResource;
use App\Models\Blog;
use App\Models\User;
use App\Services\GeminiBlogService;
use App\Support\AdminSettings;
use Filament\Actions;
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
                ->requiresConfirmation()
                ->modalHeading('Tạo bài viết bằng AI')
                ->modalDescription('AI sẽ tạo ngay 1 bài viết blog mới dựa trên danh mục ngẫu nhiên. Quá trình có thể mất 30–60 giây.')
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

                    $categoryNames = config('default_categories.names', User::defaultCategoryNames());
                    if (empty($categoryNames)) {
                        Notification::make()
                            ->title('Lỗi')
                            ->body('Không có danh mục mặc định.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $category = $categoryNames[array_rand($categoryNames)];
                    $variants = ['best', 'guide', 'comparison'];
                    $variant = $variants[array_rand($variants)];

                    $result = $gemini->generateBlog($category, $variant);

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
                        'category' => $category,
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
}
