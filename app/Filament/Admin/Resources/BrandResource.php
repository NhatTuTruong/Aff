<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BrandResource\Pages;
use App\Filament\Admin\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    
    protected static ?string $navigationLabel = 'Cửa hàng';
    
    protected static ?string $modelLabel = 'Cửa hàng';
    
    protected static ?string $pluralModelLabel = 'Cửa hàng';
    
    protected static ?string $navigationGroup = 'Quản lý';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cửa hàng')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên danh mục')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                            ])
                            ->helperText('Chọn danh mục hoặc tạo mới'),
                        Forms\Components\TextInput::make('name')
                            ->label('Tên cửa hàng')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Tự động tạo từ tên'),
                        Forms\Components\TextInput::make('domain')
                            ->label('Domain')
                            ->maxLength(255)
                            ->placeholder('example.com')
                            ->helperText('Nhập domain để tự động lấy logo (ví dụ: amazon.com)')
                            ->live(onBlur: true),
                        Forms\Components\Hidden::make('logo_preview_path')
                            ->default(null),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('fetchLogo')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->label('Lấy logo')
                                ->color('success')
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                    $domain = $get('domain');
                                    if (empty($domain)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Vui lòng nhập domain')
                                            ->danger()
                                            ->send();
                                        return;
                                    }
                                    
                                    // Clean domain
                                    $domain = preg_replace('/^https?:\/\//', '', $domain);
                                    $domain = preg_replace('/^www\./', '', $domain);
                                    $domain = explode('/', $domain)[0];
                                    $domain = trim($domain);
                                    
                                    if (empty($domain)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Domain không hợp lệ')
                                            ->danger()
                                            ->send();
                                        return;
                                    }
                                    
                                    try {
                                        // Try multiple free logo APIs
                                        $logoUrls = [
                                            "https://logo.clearbit.com/{$domain}",
                                            "https://www.google.com/s2/favicons?domain={$domain}&sz=128",
                                            "https://icons.duckduckgo.com/ip3/{$domain}.ico",
                                        ];
                                        
                                        // Ensure $logoUrls is an array
                                        if (!is_array($logoUrls)) {
                                            $logoUrls = [];
                                        }
                                        
                                        $logoDownloaded = false;
                                        
                                        foreach ($logoUrls as $logoUrl) {
                                            if (!is_string($logoUrl) || empty($logoUrl)) {
                                                continue;
                                            }
                                            
                                            try {
                                                $httpResponse = \Illuminate\Support\Facades\Http::timeout(5)
                                                    ->withOptions([
                                                        'verify' => false,
                                                        'http_errors' => false,
                                                    ])
                                                    ->get($logoUrl);
                                                
                                                // Ensure we have a valid response object
                                                if (!is_object($httpResponse) || !method_exists($httpResponse, 'successful')) {
                                                    continue;
                                                }
                                                
                                                if (!$httpResponse->successful()) {
                                                    continue;
                                                }
                                                
                                                $imageContent = $httpResponse->body();
                                                
                                                // Ensure imageContent is a string
                                                if (!is_string($imageContent) || empty($imageContent)) {
                                                    continue;
                                                }
                                                
                                                $imageInfo = @getimagesizefromstring($imageContent);
                                                
                                                if ($imageInfo !== false && is_array($imageInfo) && isset($imageInfo[2])) {
                                                    // Valid image
                                                    $extension = image_type_to_extension($imageInfo[2], false) ?: 'png';
                                                    $filename = \Illuminate\Support\Str::slug($domain) . '_' . time() . '.' . $extension;
                                                    $path = 'brands/' . $filename;
                                                    
                                                    try {
                                                        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $imageContent);
                                                        
                                                        // Store preview path (not yet in main image field)
                                                        $previewUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                                                        $set('logo_preview_path', $path);
                                                        $logoDownloaded = true;
                                                        
                                                        \Filament\Notifications\Notification::make()
                                                            ->title('Đã tải logo thành công!')
                                                            ->body("Logo từ: " . parse_url($logoUrl, PHP_URL_HOST) . ". Nhấn 'Upload' để thêm vào form.")
                                                            ->success()
                                                            ->send();
                                                        break;
                                                    } catch (\Exception $storageError) {
                                                        // Continue to next URL if storage fails
                                                        continue;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                // Try next URL
                                                continue;
                                            } catch (\Throwable $e) {
                                                // Try next URL
                                                continue;
                                            }
                                        }
                                        
                                        if (!$logoDownloaded) {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Không tìm thấy logo')
                                                ->body('Không thể tải logo từ domain này. Vui lòng upload thủ công.')
                                                ->warning()
                                                ->send();
                                        }
                                    } catch (\Throwable $e) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Lỗi khi tải logo')
                                            ->body($e->getMessage())
                                            ->danger()
                                            ->send();
                                    }
                                }),
                            Forms\Components\Actions\Action::make('uploadLogo')
                                ->icon('heroicon-o-arrow-up-tray')
                                ->label('Upload')
                                ->color('primary')
                                ->visible(fn (Forms\Get $get) => !empty($get('logo_preview_path')))
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                    $previewPath = $get('logo_preview_path');
                                    if (empty($previewPath)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Không có logo để upload')
                                            ->warning()
                                            ->send();
                                        return;
                                    }
                                    
                                    // Move from preview to main image field
                                    // Filament FileUpload with disk('public') expects path relative to storage/app/public
                                    // Path format: 'brands/filename.png'
                                    // Ensure path doesn't have leading slash
                                    $imagePath = ltrim($previewPath, '/');
                                    $set('image', $imagePath);
                                    $set('logo_preview_path', null);
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('Đã upload logo thành công!')
                                        ->body('Logo đã được thêm vào form. Nếu không thấy ảnh, vui lòng refresh trang.')
                                        ->success()
                                        ->send();
                                }),
                        ])
                            ->columnSpanFull(),
                        Forms\Components\View::make('filament.admin.components.logo-preview')
                            ->viewData(fn (Forms\Get $get) => [
                                'previewPath' => $get('logo_preview_path'),
                            ])
                            ->visible(fn (Forms\Get $get) => !empty($get('logo_preview_path')))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('events')
                            ->label('Events')
                            ->maxLength(255)
                            ->placeholder('e.g., Uncategorized'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Media & Nội dung')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Hình ảnh')
                            ->image()
                            ->directory('brands')
                            ->maxSize(5120)
                            ->helperText('Tải lên hình ảnh brand hoặc sử dụng nút "Lấy logo" ở trên'),
                            
                        Forms\Components\Toggle::make('approved')
                            ->label('Duyệt bài')
                            ->default(false),
                        Forms\Components\RichEditor::make('short_description')
                            ->label('Mô tả ngắn')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strikeThrough',
                                'link',
                                'orderedList',
                                'bulletList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull()
                            ->helperText('Nếu để trống hệ thống sẽ tự động tạo mô tả'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên cửa hàng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('events')
                    ->searchable(),
                Tables\Columns\IconColumn::make('approved')
                    ->label('Duyệt bài')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approved')
                    ->label('Duyệt bài')
                    ->options([
                        true => 'Đã duyệt',
                        false => 'Chưa duyệt',
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('created_at_range')
                    ->label('Ngày tạo')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('Từ ngày'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('Đến ngày'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (\Illuminate\Database\Eloquent\Builder $q, $date) => $q->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (\Illuminate\Database\Eloquent\Builder $q, $date) => $q->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
