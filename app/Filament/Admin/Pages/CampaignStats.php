<?php

namespace App\Filament\Admin\Pages;

use App\Models\Campaign;
use App\Models\Click;
use App\Models\Coupon;
use App\Models\PageView;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CampaignStats extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.admin.pages.campaign-stats';

    protected static ?string $navigationLabel = 'Thống kê Chiến dịch';

    protected static ?string $title = 'Thống kê Chiến dịch';

    protected static ?string $navigationGroup = 'Thống Kê';

    protected static ?int $navigationSort = 2;

    public ?int $selectedCampaignId = null;

    /** 7 / 30 / 90 / all (ngày) - giá trị đang áp dụng */
    public string $period = '7';

    /** 7 / 30 / 90 / all (ngày) - giá trị chọn trên form (chỉ áp dụng khi bấm Lọc) */
    public string $periodInput = '7';

    /** Lọc theo trạng thái chiến dịch: active / draft / paused / all - đang áp dụng */
    public string $filterStatus = 'active';

    /** Trạng thái chọn trên form */
    public string $filterStatusInput = 'active';

    public ?int $filterBrandId = null;

    public ?int $filterBrandIdInput = null;

    public ?int $filterCategoryId = null;

    public ?int $filterCategoryIdInput = null;

    public ?int $filterUserId = null;

    public ?int $filterUserIdInput = null;

    public function mount(): void
    {
        $this->periodInput = $this->period;
        $this->filterStatusInput = $this->filterStatus;
        $this->filterBrandIdInput = $this->filterBrandId;
        $this->filterCategoryIdInput = $this->filterCategoryId;
        $this->filterUserIdInput = $this->filterUserId;
    }

    public function table(Table $table): Table
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $baseUserId = $isAdmin ? null : ($user?->id);
        $ownerId = $isAdmin && $this->filterUserId ? $this->filterUserId : $baseUserId;

        return $table
            ->query(
                $this->baseCampaignQuery($ownerId)
                    ->withCount('clicks')
                    ->orderByDesc('clicks_count')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Chiến dịch')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('brand.name')
                    ->label('Cửa hàng')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('clicks_count')
                    ->label('Số Clicks (tổng)')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('views_count_lifetime')
                    ->label('Số Views (tổng)')
                    ->getStateUsing(fn (Campaign $record): int => $record->pageViews()->count())
                    ->numeric()
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Action::make('viewChart')
                    ->label('Xem biểu đồ')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->slideOver()
                    ->modalHeading(fn (Campaign $record): string => "Biểu đồ: {$record->title}")
                    ->modalContent(fn (Campaign $record) => view('filament.admin.pages.campaign-stats-chart', [
                        'campaign' => $record,
                        'chartData' => $this->getChartData($record->id),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Đóng'),
            ])
            ->striped();
    }

    protected function baseCampaignQuery(?int $userId): Builder
    {
        return Campaign::query()
            ->when($this->filterStatus !== 'all', fn (Builder $q) => $q->where('status', $this->filterStatus))
            ->when(
                $userId,
                fn (Builder $query) => $query->whereHas(
                    'brand',
                    fn (Builder $brandQuery) => $brandQuery->where('user_id', $userId),
                ),
            )
            ->when(
                $this->filterBrandId,
                fn (Builder $q) => $q->where('brand_id', $this->filterBrandId),
            )
            ->when(
                $this->filterCategoryId,
                fn (Builder $q) => $q->whereHas('brand', fn (Builder $b) => $b->where('category_id', $this->filterCategoryId)),
            );
    }

    protected function getPeriodRange(): ?array
    {
        if ($this->period === 'all') {
            return null;
        }

        $days = (int) $this->period;
        if ($days <= 0) {
            return null;
        }

        $to = Carbon::now();
        $from = $to->copy()->subDays($days);

        return [$from, $to];
    }

    protected function getPreviousPeriodRange(): ?array
    {
        $range = $this->getPeriodRange();
        if (! $range) {
            return null;
        }

        [$from, $to] = $range;
        $length = $to->diffInDays($from) ?: 1;

        $prevTo = $from->copy();
        $prevFrom = $prevTo->copy()->subDays($length);

        return [$prevFrom, $prevTo];
    }

    public function getSummaryStatsProperty(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $baseUserId = $isAdmin ? null : ($user?->id);
        $ownerId = $isAdmin && $this->filterUserId ? $this->filterUserId : $baseUserId;

        $campaignIds = $this->baseCampaignQuery($ownerId)->pluck('id');
        if ($campaignIds->isEmpty()) {
            return [
                'totals' => [
                    'clicks' => 0,
                    'views' => 0,
                    'ctr' => 0,
                    'clicks_prev' => 0,
                    'views_prev' => 0,
                    'ctr_change' => 0,
                ],
                'topCampaigns' => [],
                'topBrands' => [],
                'couponQuality' => [],
                'alerts' => [],
                'devices' => [],
                'referrers' => [],
            ];
        }

        $range = $this->getPeriodRange();
        $prevRange = $this->getPreviousPeriodRange();

        $clickQuery = Click::query()->whereIn('campaign_id', $campaignIds);
        $viewQuery = PageView::query()->whereIn('campaign_id', $campaignIds);

        if ($range) {
            [$from, $to] = $range;
            $clickQuery->whereBetween('created_at', [$from, $to]);
            $viewQuery->whereBetween('created_at', [$from, $to]);
        }

        $totalClicks = (int) $clickQuery->count();
        $totalViews = (int) $viewQuery->count();
        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0.0;

        $prevClicks = 0;
        $prevViews = 0;
        $ctrChange = 0.0;

        if ($prevRange) {
            [$pFrom, $pTo] = $prevRange;
            $prevClicks = (int) Click::query()
                ->whereIn('campaign_id', $campaignIds)
                ->whereBetween('created_at', [$pFrom, $pTo])
                ->count();
            $prevViews = (int) PageView::query()
                ->whereIn('campaign_id', $campaignIds)
                ->whereBetween('created_at', [$pFrom, $pTo])
                ->count();

            $prevCtr = $prevViews > 0 ? ($prevClicks / $prevViews) * 100 : 0.0;
            $ctrChange = round($ctr - $prevCtr, 2);
        }

        // Top campaigns by clicks & CTR trong giai đoạn hiện tại
        $clicksByCampaign = Click::query()
            ->whereIn('campaign_id', $campaignIds)
            ->when($range, fn (Builder $q) => $q->whereBetween('created_at', $range))
            ->selectRaw('campaign_id, COUNT(*) as clicks')
            ->groupBy('campaign_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->pluck('clicks', 'campaign_id')
            ->toArray();

        $viewsByCampaign = PageView::query()
            ->whereIn('campaign_id', $campaignIds)
            ->when($range, fn (Builder $q) => $q->whereBetween('created_at', $range))
            ->selectRaw('campaign_id, COUNT(*) as views')
            ->groupBy('campaign_id')
            ->pluck('views', 'campaign_id')
            ->toArray();

        $campaigns = Campaign::query()
            ->with('brand')
            ->whereIn('id', array_keys($clicksByCampaign))
            ->get()
            ->keyBy('id');

        $topCampaigns = [];
        foreach ($clicksByCampaign as $cid => $clickCount) {
            $viewsCount = $viewsByCampaign[$cid] ?? 0;
            $localCtr = $viewsCount > 0 ? round(($clickCount / $viewsCount) * 100, 2) : 0.0;
            $c = $campaigns[$cid] ?? null;
            if (! $c) {
                continue;
            }
            $topCampaigns[] = [
                'id' => $cid,
                'title' => $c->title,
                'brand' => $c->brand?->name,
                'clicks' => $clickCount,
                'views' => $viewsCount,
                'ctr' => $localCtr,
            ];
        }

        // Top brands theo clicks
        $topBrands = Click::query()
            ->join('campaigns', 'clicks.campaign_id', '=', 'campaigns.id')
            ->join('brands', 'campaigns.brand_id', '=', 'brands.id')
            ->whereIn('campaigns.id', $campaignIds)
            ->when($range, fn (Builder $q) => $q->whereBetween('clicks.created_at', $range))
            ->selectRaw('brands.id as brand_id, brands.name as brand_name, COUNT(*) as clicks')
            ->groupBy('brands.id', 'brands.name')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->brand_id,
                'name' => $row->brand_name,
                'clicks' => (int) $row->clicks,
            ])
            ->toArray();

        // Coupon quality
        $couponQuery = Coupon::query()
            ->whereHas('campaign', fn (Builder $q) => $q->whereIn('id', $campaignIds));

        $couponWithCode = (clone $couponQuery)
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->count();
        $couponWithoutCode = (clone $couponQuery)
            ->where(function (Builder $q) {
                $q->whereNull('code')
                    ->orWhere('code', '');
            })
            ->count();
        $couponMissingText = (clone $couponQuery)
            ->where(function (Builder $q) {
                $q->whereNull('offer')->orWhere('offer', '')
                    ->orWhereNull('description')->orWhere('description', '');
            })
            ->count();
        $soonExpiring = (clone $couponQuery)
            ->whereNotNull('ends_at')
            ->whereBetween('ends_at', [Carbon::today(), Carbon::today()->copy()->addDays(7)])
            ->count();

        // Alerts
        $alertsMissingLogo = (clone $this->baseCampaignQuery($ownerId))
            ->whereHas('brand', fn (Builder $b) => $b->whereNull('image')->orWhere('image', ''))
            ->count();
        $alertsMissingIntro = (clone $this->baseCampaignQuery($ownerId))
            ->where(function (Builder $q) {
                $q->whereNull('intro')->orWhere('intro', '');
            })
            ->count();
        $alertsMissingCategory = (clone $this->baseCampaignQuery($ownerId))
            ->whereHas('brand', fn (Builder $b) => $b->whereNull('category_id'))
            ->count();
        $alertsMissingAffiliate = (clone $this->baseCampaignQuery($ownerId))
            ->where(function (Builder $q) {
                $q->whereNull('affiliate_url')->orWhere('affiliate_url', '');
            })
            ->count();

        // Device stats (Page views)
        $deviceStats = PageView::query()
            ->whereIn('campaign_id', $campaignIds)
            ->when($range, fn (Builder $q) => $q->whereBetween('created_at', $range))
            ->selectRaw("COALESCE(device_type, 'unknown') as device_type, COUNT(*) as total")
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->device_type => (int) $row->total])
            ->toArray();

        // Top referrers (exclude internal admin pages like /admin/*)
        $referrers = PageView::query()
            ->whereIn('campaign_id', $campaignIds)
            ->when($range, fn (Builder $q) => $q->whereBetween('created_at', $range))
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->where(function (Builder $q) {
                $q->where('referer', 'not like', '%/admin/%')
                  ->where('referer', 'not like', '%/admin');
            })
            ->selectRaw('referer, COUNT(*) as total')
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'referer' => $row->referer,
                'total' => (int) $row->total,
            ])
            ->toArray();

        return [
            'totals' => [
                'clicks' => $totalClicks,
                'views' => $totalViews,
                'ctr' => $ctr,
                'clicks_prev' => $prevClicks,
                'views_prev' => $prevViews,
                'ctr_change' => $ctrChange,
            ],
            'topCampaigns' => $topCampaigns,
            'topBrands' => $topBrands,
            'couponQuality' => [
                'with_code' => $couponWithCode,
                'without_code' => $couponWithoutCode,
                'missing_text' => $couponMissingText,
                'soon_expiring' => $soonExpiring,
            ],
            'alerts' => [
                'missing_logo' => $alertsMissingLogo,
                'missing_intro' => $alertsMissingIntro,
                'missing_category' => $alertsMissingCategory,
                'missing_affiliate' => $alertsMissingAffiliate,
            ],
            'devices' => $deviceStats,
            'referrers' => $referrers,
        ];
    }

    public function getBrandOptionsProperty(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        $query = \App\Models\Brand::query()
            ->orderBy('name');

        if (! $isAdmin && $user) {
            $query->where('user_id', $user->id);
        }

        return $query->pluck('name', 'id')->toArray();
    }

    public function getCategoryOptionsProperty(): array
    {
        $user = Filament::auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

        $query = \App\Models\Category::query()
            ->orderBy('name');

        if (! $isAdmin && $user) {
            $query->where('user_id', $user->id);
        }

        return $query->pluck('name', 'id')->toArray();
    }

    public function getUserOptionsProperty(): array
    {
        return User::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getChartData(int $campaignId): array
    {
        $range = $this->getPeriodRange();

        $clicksQuery = Click::query()
            ->where('campaign_id', $campaignId);
        $viewsQuery = PageView::query()
            ->where('campaign_id', $campaignId);

        if ($range) {
            [$from, $to] = $range;
            $clicksQuery->whereBetween('created_at', [$from, $to]);
            $viewsQuery->whereBetween('created_at', [$from, $to]);
        }

        $clicks = $clicksQuery
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $views = $viewsQuery
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = array_values(array_unique(array_merge(array_keys($clicks), array_keys($views))));
        sort($labels);

        return [
            'labels' => $labels,
            'clicks' => array_map(fn (string $d) => $clicks[$d] ?? 0, $labels),
            'views' => array_map(fn (string $d) => $views[$d] ?? 0, $labels),
        ];
    }

    /** Áp dụng các giá trị filter khi bấm nút "Lọc" */
    public function applyFilters(): void
    {
        $this->period = $this->periodInput;
        $this->filterStatus = $this->filterStatusInput;
        $this->filterBrandId = $this->filterBrandIdInput ?: null;
        $this->filterCategoryId = $this->filterCategoryIdInput ?: null;
        $this->filterUserId = $this->filterUserIdInput ?: null;

        $this->resetPage($this->getTablePaginationPageName());
    }

    public static function getSlug(): string
    {
        return 'campaign-stats';
    }
}
