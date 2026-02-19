<?php

namespace App\Filament\Imports;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Coupon;
use App\Services\LogoFromDomainService;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class CampaignImporter extends Importer
{
    protected static ?string $model = Campaign::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category')
                ->label('Danh mục')
                ->rules(['nullable', 'max:255'])
                ->example('Thời trang')
                ->exampleHeader('Danh mục')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('brand')
                ->label('Cửa hàng (tên hoặc ID)')
                ->guess(['Cửa hàng'])
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Nexalabs Shop')
                ->exampleHeader('Cửa hàng')
                ->fillRecordUsing(function (Campaign $record, string $state, array $data): void {
                    static::fillBrandForRecord($record, $state, $data);
                }),
            ImportColumn::make('domain')
                ->label('Domain (lấy logo)')
                ->rules(['nullable', 'max:255'])
                ->example('nexalabs.com')
                ->exampleHeader('Domain')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('title')
                ->label('Tiêu đề')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Black Friday Sale 2025')
                ->exampleHeader('Tiêu đề'),
            ImportColumn::make('slug')
                ->label('Slug')
                ->rules(['nullable', 'max:255'])
                ->helperText('Để trống sẽ tự tạo từ tiêu đề')
                ->example('black-friday-sale-2025')
                ->exampleHeader('Slug'),
            ImportColumn::make('intro')
                ->label('Giới thiệu (HTML)')
                ->guess(['Giới thiệu'])
                ->rules(['nullable', 'max:65535'])
                ->example('<p>Giới thiệu chiến dịch</p>')
                ->exampleHeader('Giới thiệu'),
            ImportColumn::make('status')
                ->label('Trạng thái')
                ->rules(['required', 'in:draft,active,paused'])
                ->example('active')
                ->exampleHeader('Trạng thái'),
            ImportColumn::make('template')
                ->label('Template')
                ->rules(['required', 'in:template1,template2,template3'])
                ->example('template1')
                ->exampleHeader('Template'),
            ImportColumn::make('affiliate_url')
                ->label('URL Affiliate')
                ->requiredMapping()
                ->rules(['required', 'url'])
                ->example('https://example.com/?ref=xxx')
                ->exampleHeader('URL Affiliate'),
            ImportColumn::make('coupon_codes')
                ->label('Mã giảm giá (phân cách bằng ,)')
                ->guess(['Mã giảm giá'])
                ->rules(['nullable', 'max:500'])
                ->example('SAVE10, SAVE20')
                ->exampleHeader('Mã giảm giá')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('coupon_offers')
                ->label('Offer (phân cách bằng ,)')
                ->guess(['Offer'])
                ->rules(['nullable', 'max:500'])
                ->example('10%, 20$')
                ->exampleHeader('Offer')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('coupon_descriptions')
                ->label('Mô tả mã giảm giá (phân cách bằng ;)')
                ->guess(['Mô tả'])
                ->rules(['nullable', 'max:2000'])
                ->example('Giảm 10%; Giảm 20$')
                ->exampleHeader('Mô tả')
                ->fillRecordUsing(fn () => null),
        ];
    }

    public function resolveRecord(): ?Campaign
    {
        return new Campaign();
    }

    public function __invoke(array $row): void
    {
        app()->instance('current_import_id', $this->import->getKey());
        try {
            parent::__invoke($row);
        } catch (RowImportFailedException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new RowImportFailedException(
                strlen($e->getMessage()) > 200 ? substr($e->getMessage(), 0, 200) . '...' : $e->getMessage()
            );
        } finally {
            app()->forgetInstance('current_import_id');
        }
    }

    protected function beforeSave(): void
    {
        $this->record->import_id = $this->import->getKey();
        
        // Lấy user_code từ brand
        $user = $this->record->brand?->user ?? \App\Models\User::find($this->record->brand?->user_id);
        $userCode = $user?->code ?? '00000';
        
        $baseSlug = $this->record->slug ?: Str::slug($this->record->title);
        $fullSlug = "{$userCode}/{$baseSlug}";
        
        // Kiểm tra slug đã tồn tại trong cùng user
        if (Campaign::where('slug', $fullSlug)
            ->where('id', '!=', $this->record->id ?? 0)
            ->whereHas('brand', function ($q) use ($user) {
                if ($user) {
                    $q->where('user_id', $user->id);
                }
            })
            ->exists()) {
            throw new RowImportFailedException("Slug chiến dịch đã tồn tại: {$fullSlug}");
        }
        
        $this->record->slug = $fullSlug;
    }

    protected function afterSave(): void
    {
        $this->createCoupons();
    }

    public static function fillBrandForRecord(Campaign $record, string $state, array $data): void
    {
        $categoryName = trim((string) ($data['category'] ?? ''));
        $domain = trim((string) ($data['domain'] ?? ''));

        $category = static::resolveOrCreateCategory($categoryName);
        $brand = static::resolveOrCreateBrand($state, $category, $domain);

        $record->brand_id = $brand->id;
    }

    protected function createCoupons(): void
    {
        $codes = $this->parseList($this->data['coupon_codes'] ?? '', ',');
        $offers = $this->parseList($this->data['coupon_offers'] ?? '', ',');
        $descriptions = $this->parseList($this->data['coupon_descriptions'] ?? '', ';');

        $count = max(count($codes), count($offers), count($descriptions));
        if ($count === 0) {
            return;
        }

        for ($i = 0; $i < $count; $i++) {
            Coupon::create([
                'campaign_id' => $this->record->id,
                'code' => $codes[$i] ?? '',
                'offer' => $offers[$i] ?? '',
                'description' => $descriptions[$i] ?? '',
            ]);
        }
    }

    protected function parseList(?string $value, string $separator = ','): array
    {
        if (empty(trim((string) $value))) {
            return [];
        }
        $sep = preg_quote($separator, '/');
        return array_filter(array_map('trim', preg_split("/{$sep}/", (string) $value)));
    }

    public static function resolveOrCreateCategory(string $name): Category
    {
        $importId = app()->bound('current_import_id') ? app('current_import_id') : null;

        if (empty($name)) {
            return Category::first() ?? Category::create([
                'name' => 'Uncategorized',
                'slug' => 'uncategorized',
                'is_active' => true,
                'import_id' => $importId,
            ]);
        }

        $userId = \Illuminate\Support\Facades\Auth::id();
        $user = \App\Models\User::find($userId);
        $userCode = $user?->code ?? '00000';
        
        $baseSlug = Str::slug($name);
        $fullSlug = "{$userCode}/{$baseSlug}";
        
        // Tìm category theo slug đầy đủ hoặc name trong cùng user
        $category = Category::where(function ($q) use ($fullSlug, $name, $userId) {
            $q->where('slug', $fullSlug)
              ->orWhere(function ($q2) use ($name, $userId) {
                  $q2->where('name', $name)->where('user_id', $userId);
              });
        })->first();

        return $category ?? Category::create([
            'name' => $name,
            'slug' => $fullSlug,
            'is_active' => true,
            'import_id' => $importId,
            'user_id' => $userId,
        ]);
    }

    public static function resolveOrCreateBrand(string $nameOrId, Category $category, string $domain = ''): Brand
    {
        $name = trim($nameOrId);
        if (empty($name)) {
            throw new RowImportFailedException('Tên cửa hàng là bắt buộc.');
        }

        if (is_numeric($name)) {
            $brand = Brand::find((int) $name);
            if ($brand) {
                return $brand;
            }
            throw new RowImportFailedException("Không tìm thấy cửa hàng với ID: {$name}");
        }

        $userId = \Illuminate\Support\Facades\Auth::id();
        $user = \App\Models\User::find($userId);
        $userCode = $user?->code ?? '00000';
        
        $baseSlug = Str::slug($name);
        $fullSlug = "{$userCode}/{$baseSlug}";
        
        // Tìm brand theo slug đầy đủ hoặc name trong cùng user
        $brand = Brand::where(function ($q) use ($fullSlug, $name, $userId) {
            $q->where('slug', $fullSlug)
              ->orWhere(function ($q2) use ($name, $userId) {
                  $q2->where('name', $name)->where('user_id', $userId);
              });
        })->first();

        if ($brand) {
            if (! $brand->image && ! empty($domain)) {
                try {
                    $logoPath = LogoFromDomainService::fetchAndSave($domain);
                    if ($logoPath) {
                        $brand->update(['image' => $logoPath]);
                    }
                } catch (\Throwable) {
                    // Bỏ qua lỗi logo, không làm dừng import
                }
            }
            return $brand;
        }

        $imagePath = null;
        if (! empty($domain)) {
            try {
                $imagePath = LogoFromDomainService::fetchAndSave($domain);
            } catch (\Throwable) {
                // Bỏ qua lỗi logo
            }
        }

        // Kiểm tra trùng trong cùng user
        if (Brand::where(function ($q) use ($fullSlug, $name, $userId) {
            $q->where('slug', $fullSlug)
              ->orWhere(function ($q2) use ($name, $userId) {
                  $q2->where('name', $name)->where('user_id', $userId);
              });
        })->exists()) {
            throw new RowImportFailedException("Tên cửa hàng đã tồn tại: {$name}");
        }

        $importId = app()->bound('current_import_id') ? app('current_import_id') : null;

        return Brand::create([
            'name' => $name,
            'slug' => $fullSlug,
            'category_id' => $category->id,
            'image' => $imagePath,
            'approved' => true,
            'import_id' => $importId,
            'user_id' => $userId,
        ]);
    }

    public function getJobConnection(): ?string
    {
        return 'sync'; // sync: import chạy ngay. database: cần php artisan queue:work để hiển thị tiến trình %
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $success = (int) $import->successful_rows;
        $failed = $import->getFailedRowsCount();

        $body = 'Đã upload thành công ' . number_format($success) . ' chiến dịch.';
        if ($failed > 0) {
            $body .= ' Không import được ' . number_format($failed) . ' chiến dịch. Bạn có thể tải file CSV chứa các dòng lỗi.';
        }

        return $body;
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        $success = (int) $import->successful_rows;
        $failed = $import->getFailedRowsCount();

        return $failed > 0
            ? "Import: {$success} thành công, {$failed} thất bại"
            : "Import hoàn tất: {$success} chiến dịch";
    }
}
