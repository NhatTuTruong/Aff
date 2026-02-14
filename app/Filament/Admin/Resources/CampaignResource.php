<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CampaignResource\Pages;
use App\Filament\Admin\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    
    protected static ?string $navigationLabel = 'Chiến dịch';
    
    protected static ?string $modelLabel = 'Chiến dịch';
    
    protected static ?string $pluralModelLabel = 'Chiến dịch';
    
    protected static ?string $navigationGroup = 'Quản lý';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\Select::make('brand_id')
                            ->label('Cửa hàng')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state) {
                                    $brand = Brand::find($state);
                                    if ($brand) {
                                        // Tự động điền title nếu đang trống
                                        if (empty($get('title'))) {
                                            $set('title', $brand->name);
                                        }
                                        // Tự động điền slug nếu đang trống
                                        if (empty($get('slug'))) {
                                            $set('slug', \Illuminate\Support\Str::slug($brand->name));
                                        }
                                    }
                                }
                            })
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên cửa hàng')
                                    ->required(),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                            ])
                            ->helperText('Chọn brand hoặc tạo mới'),
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Tự động tạo từ tiêu đề. URL: /review/{slug}'),
                        Forms\Components\RichEditor::make('intro')
                            ->label('Giới thiệu')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strikeThrough',
                                'link',
                                'image',
                                'orderedList',
                                'bulletList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull()
                            ->extraInputAttributes([
                                'style' => 'min-height: 200px;',
                            ])
                    ])->columns(2),
                
                Forms\Components\Section::make('Cài đặt chiến dịch')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'draft' => 'Bản nháp',
                                'active' => 'Hoạt động',
                                'paused' => 'Tạm dừng',
                            ])
                            ->required()
                            ->default('active'),
                        Forms\Components\Select::make('template')
                            ->label('Giao diện (Template)')
                            ->options([
                                'template1' => 'Template 1',
                                'template2' => 'Template 2',
                                'template3' => 'Template 3',
                            ])
                            ->required()
                            ->default('template1')
                            ->helperText('Chọn template landing page'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Hình ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo chiến dịch')
                            ->image()
                            ->directory('campaigns/logo')
                            ->maxSize(2048)
                            ->helperText('Logo sẽ hiển thị trên landing page'),
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Ảnh bìa')
                            ->image()
                            ->directory('campaigns/cover')
                            ->maxSize(5120)
                            ->helperText('Ảnh bìa chính của chiến dịch'),
                        Forms\Components\FileUpload::make('product_images')
                            ->label('Ảnh sản phẩm')
                            ->image()
                            ->directory('campaigns/products')
                            ->maxSize(5120)
                            ->multiple()
                            ->maxFiles(10)
                            ->helperText('Có thể upload nhiều ảnh sản phẩm (tối đa 10 ảnh)'),
                    ])
                    ->collapsible()
                    ->collapsed(),
                
                Forms\Components\Section::make('Affiliate & Nút kêu gọi (CTA)')
                    ->schema([
                        Forms\Components\TextInput::make('affiliate_url')
                            ->label('URL Affiliate')
                            ->required()
                            ->url()
                            ->columnSpanFull()
                            ->helperText('URL affiliate đầy đủ với tham số tracking'),
                        Forms\Components\TextInput::make('cta_text')
                            ->label('Nội dung nút kêu gọi (CTA)')
                            ->required()
                            ->maxLength(255)
                            ->default('Get Coupon Alerts'),
                    ]),
                
                Forms\Components\Section::make('Mã giảm giá')
                    ->schema([
                        Forms\Components\Repeater::make('couponItems')
                            ->relationship('couponItems')
                            ->label('Danh sách mã giảm giá')
                            ->schema(function () {
                                $descriptionTemplates = [
                                    'Get up to an additional :offer off the entire website when you check out.',
                                    'Extra :offer OFF Storewide – Limited Time',
                                    'Save :offer OFF on All Items at Checkout',
                                    'Up to :offer OFF Selected Items',
                                    'Extra :offer OFF Each Item at Checkout',
                                    'Free Shipping on All Orders',
                                    'Free Shipping on Orders Over :offer',
                                    'Buy 2 Get 1 Free – Limited Offer',
                                    'Extra Savings Applied at Checkout',
                                    'Exclusive Coupon – Limited Time Only',
                                    'Extra :offer OFF Automatically at Checkout',
                                    'Bonus :offer OFF with Promo Code',
                                    'Additional :offer OFF on Sale Items',
                                    'Extra Discount on All Products',
                                    'Flash Sale – Extra :offer OFF Today',
                                    'Limited Stock – Extra :offer OFF',
                                    'Seasonal Sale – Up to :offer OFF',
                                    'Special Deal – Save More at Checkout',
                                    'Extra :offer OFF on Bestsellers',
                                    'New Customer Deal – Extra :offer OFF',
                                    'Extra :offer OFF on Your Entire Order',
                                    'Discounts up to :offer',
                                ];

                                return [
                                    Forms\Components\TextInput::make('code')
                                        ->label('Mã giảm giá')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('offer')
                                        ->label('Offer')
                                        ->maxLength(50)
                                        ->live(onBlur: false)
                                        ->helperText('Nhập offer để xem gợi ý mô tả'),
                                    Forms\Components\Select::make('description_suggestion')
                                        ->label('Gợi ý mô tả')
                                        ->searchable()
                                        ->preload()
                                        ->options(function (Get $get) use ($descriptionTemplates): array {
                                            $rawOffer = trim((string) $get('offer'));

                                            if ($rawOffer === '') {
                                                return [];
                                            }

                                            // Chuẩn hoá Offer: chỉ giữ phần chứa số + % hoặc $, ví dụ 10%, $20, 20$
                                            $cleanSource = strtolower($rawOffer);
                                            $cleanSource = preg_replace('/off/i', '', $cleanSource);

                                            $match = null;
                                            if (preg_match('/([\$]?\d+%?|\d+[\$])/i', $cleanSource, $m)) {
                                                $match = trim($m[0]);
                                            }

                                            if (empty($match)) {
                                                return [];
                                            }

                                            // Nếu dạng "20$" thì vẫn giữ nguyên như user nhập
                                            $offer = $match;

                                            return collect($descriptionTemplates)
                                                ->mapWithKeys(function (string $template) use ($offer) {
                                                    $text = str_replace(':offer', $offer, $template);
                                                    return [$text => $text];
                                                })
                                                ->toArray();
                                        })
                                        ->live()
                                        ->visible(fn (Get $get) => trim((string) $get('offer')) !== '')
                                        ->helperText('Chọn một gợi ý mô tả từ danh sách')
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            if (! empty($state)) {
                                                // Khi chọn gợi ý thì đổ vào trường mô tả chính, cho phép user sửa tiếp
                                                $set('description', $state);
                                                // Xoá lựa chọn gợi ý để tránh nhầm
                                                $set('description_suggestion', null);
                                            }
                                        }),
                                    Forms\Components\TextInput::make('description')
                                        ->label('Mô tả ngắn')
                                        ->maxLength(500)
                                        ->helperText('Mô tả chi tiết về mã giảm giá (có thể chọn từ gợi ý ở trên)'),
                                ];
                            })
                            // Mỗi item (coupon) là 1 "thẻ", các thẻ xếp lưới 3 cột
                            ->grid(3)
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['code'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Cửa hàng')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'paused' => 'warning',
                        'draft' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'paused' => 'Tạm dừng',
                        'draft' => 'Bản nháp',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Phụ đề')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cta_text')
                    ->label('Nội dung nút CTA')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template')
                    ->label('Template')
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('brand')
                    ->label('Lọc theo cửa hàng')
                    ->relationship('brand', 'name'),
                Tables\Filters\Filter::make('has_offers')
                    ->label('Có Offer / Mã giảm giá')
                    ->query(function (Builder $query) {
                        return $query
                            ->whereNotNull('coupon_code')
                            ->orWhereHas('couponItems');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->tooltip('Sửa'),
                Tables\Actions\Action::make('view_landing')
                    ->label('')
                    ->url(fn ($record) => route('landing.show', $record->slug))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->tooltip('Xem landing page'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Xóa chiến dịch'),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
