<x-filament-panels::page>
    <style>
        .cs-page {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1600px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
        .cs-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            justify-content: space-between;
        }
        .cs-filters-desc {
            flex: 1 1 100%;
        }
        @media (min-width: 640px) {
            .cs-filters-desc { flex: 1 1 auto; }
        }
        .cs-filters-right {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: flex-end;
        }
        .cs-filters-right > div { min-width: 120px; }
        .cs-card {
            border-radius: 1rem;
            border: 1px solid rgb(229 231 235);
            background: white;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05);
        }
        .dark .cs-card {
            border-color: rgb(55 65 81);
            background: rgb(17 24 39);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.2);
        }
        .cs-card-table {
            padding: 0;
        }
        .cs-card-table .cs-section-title {
            padding: 1rem 1.25rem 0.75rem;
        }
        .cs-summary-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .cs-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .cs-summary-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        .cs-summary-card {
            padding: 1.25rem 1rem;
        }
        .cs-main-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            align-items: start;
        }
        @media (min-width: 1280px) {
            .cs-main-grid {
                grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr);
                gap: 1.5rem;
            }
        }
        .cs-main-left {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .cs-main-right {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            min-width: 0;
        }
        @media (min-width: 640px) {
            .cs-main-right { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 1280px) {
            .cs-main-right { grid-template-columns: 1fr; }
        }
        .cs-table-scroll {
            max-height: 420px;
            overflow-y: auto;
        }
        .cs-table-scroll table {
            width: 100%;
            border-collapse: collapse;
        }
        .cs-card-table table {
            width: 100%;
        }
        .cs-table-num { white-space: nowrap; text-align: right; }
        .cs-section-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: rgb(31 41 55);
            margin-bottom: 0.75rem;
        }
        .dark .cs-section-title { color: rgb(243 244 246); }
        .cs-table-block .fi-ta-table {
            margin: 0 !important;
        }
    </style>
    <div class="cs-page">
        {{-- Filters --}}
        <div class="cs-filters">
            <div class="cs-filters-desc">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Thống kê hiệu suất chiến dịch theo Clicks / Views / CTR. Lọc theo khoảng thời gian, trạng thái và cửa hàng.
                </p>
            </div>
            <div class="cs-filters-right text-sm">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Thời gian</label>
                    <select wire:model="periodInput" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="7">7 ngày</option>
                        <option value="30">30 ngày</option>
                        <option value="90">90 ngày</option>
                        <option value="all">Tất cả</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái</label>
                    <select wire:model="filterStatusInput" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="active">Đang hoạt động</option>
                        <option value="draft">Bản nháp</option>
                        <option value="paused">Tạm dừng</option>
                        <option value="all">Tất cả</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Cửa hàng</label>
                    <select wire:model="filterBrandIdInput" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Tất cả</option>
                        @foreach($this->brandOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Danh mục</label>
                    <select wire:model="filterCategoryIdInput" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">Tất cả</option>
                        @foreach($this->categoryOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button"
                        wire:click="applyFilters"
                        class="inline-flex items-center rounded-lg border border-transparent bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400">
                        Lọc
                    </button>
                </div>
            </div>
        </div>

        @php($stats = $this->summaryStats)

        {{-- Summary cards --}}
        <div class="cs-summary-grid">
            <div class="cs-card cs-summary-card">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Clicks</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($stats['totals']['clicks'] ?? 0) }}
                </div>
                @if(($stats['totals']['clicks_prev'] ?? 0) > 0)
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Trước đó: {{ number_format($stats['totals']['clicks_prev']) }}
                    </div>
                @endif
            </div>
            <div class="cs-card cs-summary-card">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Views</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($stats['totals']['views'] ?? 0) }}
                </div>
                @if(($stats['totals']['views_prev'] ?? 0) > 0)
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Trước đó: {{ number_format($stats['totals']['views_prev']) }}
                    </div>
                @endif
            </div>
            <div class="cs-card cs-summary-card">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">CTR</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($stats['totals']['ctr'] ?? 0, 2) }}%
                </div>
                <div class="mt-1 text-xs {{ ($stats['totals']['ctr_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ ($stats['totals']['ctr_change'] ?? 0) >= 0 ? '▲' : '▼' }}
                    {{ number_format(abs($stats['totals']['ctr_change'] ?? 0), 2) }} điểm so với kỳ trước
                </div>
            </div>
            <div class="cs-card cs-summary-card">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Cảnh báo</div>
                <ul class="mt-1 space-y-1 text-xs text-gray-600 dark:text-gray-300">
                    <li>
                        <a href="{{ \App\Filament\Admin\Resources\CampaignResource::getUrl('index', ['alert' => 'missing_logo']) }}"
                           class="inline-flex items-center gap-1 text-gray-700 hover:text-amber-600 dark:text-gray-200 dark:hover:text-amber-400">
                            <span>Thiếu logo:</span>
                            <strong>{{ $stats['alerts']['missing_logo'] ?? 0 }}</strong>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Admin\Resources\CampaignResource::getUrl('index', ['alert' => 'missing_intro']) }}"
                           class="inline-flex items-center gap-1 text-gray-700 hover:text-amber-600 dark:text-gray-200 dark:hover:text-amber-400">
                            <span>Thiếu giới thiệu:</span>
                            <strong>{{ $stats['alerts']['missing_intro'] ?? 0 }}</strong>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Admin\Resources\CampaignResource::getUrl('index', ['alert' => 'missing_category']) }}"
                           class="inline-flex items-center gap-1 text-gray-700 hover:text-amber-600 dark:text-gray-200 dark:hover:text-amber-400">
                            <span>Thiếu danh mục:</span>
                            <strong>{{ $stats['alerts']['missing_category'] ?? 0 }}</strong>
                        </a>
                    </li>
                    <li>
                        <a href="{{ \App\Filament\Admin\Resources\CampaignResource::getUrl('index', ['alert' => 'missing_affiliate']) }}"
                           class="inline-flex items-center gap-1 text-gray-700 hover:text-amber-600 dark:text-gray-200 dark:hover:text-amber-400">
                            <span>Thiếu affiliate URL:</span>
                            <strong>{{ $stats['alerts']['missing_affiliate'] ?? 0 }}</strong>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Main: Top campaigns + Side blocks --}}
        <div class="cs-main-grid">
            <div class="cs-main-left">
                <div>
                    <h3 class="cs-section-title">Top chiến dịch theo Clicks</h3>
                    <div class="cs-card cs-card-table overflow-hidden">
                        <div class="cs-table-scroll">
                            <table class="divide-y divide-gray-200 text-sm dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800/80 sticky top-0 z-[1]">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Chiến dịch</th>
                                        <th class="px-3 py-2.5 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Clicks</th>
                                        <th class="px-3 py-2.5 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                                        <th class="px-3 py-2.5 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">CTR</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    @forelse($stats['topCampaigns'] as $c)
                                        <tr>
                                            <td class="px-3 py-2.5">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $c['title'] }}</div>
                                                @if(!empty($c['brand']))
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $c['brand'] }}</div>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2.5 text-right font-medium">{{ number_format($c['clicks']) }}</td>
                                            <td class="px-3 py-2.5 text-right">{{ number_format($c['views']) }}</td>
                                            <td class="px-3 py-2.5 text-right">{{ number_format($c['ctr'], 2) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-4 text-center text-xs text-gray-500 dark:text-gray-400">
                                                Chưa có dữ liệu trong khoảng thời gian này.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="cs-section-title">Top nguồn truy cập</h3>
                    <div class="cs-card cs-card-table overflow-hidden">
                        <div class="cs-table-scroll">
                            <table class="divide-y divide-gray-200 text-xs dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800/60">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Referrer</th>
                                        <th class="px-3 py-2 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($stats['referrers'] as $ref)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <span class="truncate block max-w-full" title="{{ $ref['referer'] }}">{{ $ref['referer'] }}</span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-semibold cs-table-num">{{ number_format($ref['total']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-3 py-3 text-center text-[11px] text-gray-500 dark:text-gray-400">Chưa có dữ liệu.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cs-main-right">
                <div class="cs-card cs-card-table">
                    <h3 class="cs-section-title">Top cửa hàng theo Clicks</h3>
                    <table class="divide-y divide-gray-200 text-xs dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th class="px-2.5 py-1.5 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Cửa hàng</th>
                                <th class="px-2.5 py-1.5 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Clicks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($stats['topBrands'] as $b)
                                <tr>
                                    <td class="px-2.5 py-1.5 truncate max-w-[140px]" title="{{ $b['name'] }}">{{ $b['name'] }}</td>
                                    <td class="px-2.5 py-1.5 text-right font-semibold">{{ number_format($b['clicks']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-2.5 py-2 text-center text-[11px] text-gray-500 dark:text-gray-400">Chưa có dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="cs-card cs-card-table">
                    <h3 class="cs-section-title">Chất lượng Coupon</h3>
                    <table class="divide-y divide-gray-200 text-xs dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th class="px-2.5 py-1.5 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Loại</th>
                                <th class="px-2.5 py-1.5 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr>
                                <td class="px-2.5 py-1.5">Có code</td>
                                <td class="px-2.5 py-1.5 text-right font-semibold">{{ $stats['couponQuality']['with_code'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1.5">Chỉ deal (không code)</td>
                                <td class="px-2.5 py-1.5 text-right font-semibold">{{ $stats['couponQuality']['without_code'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1.5">Thiếu offer/mô tả</td>
                                <td class="px-2.5 py-1.5 text-right font-semibold">{{ $stats['couponQuality']['missing_text'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1.5">Sắp hết hạn (7 ngày)</td>
                                <td class="px-2.5 py-1.5 text-right font-semibold">{{ $stats['couponQuality']['soon_expiring'] ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="cs-card cs-card-table">
                    <h3 class="cs-section-title">Thiết bị</h3>
                    <table class="divide-y divide-gray-200 text-xs dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th class="px-2.5 py-1.5 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Thiết bị</th>
                                <th class="px-2.5 py-1.5 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($stats['devices'] as $device => $total)
                                <tr>
                                    <td class="px-2.5 py-1.5">{{ ucfirst($device) }}</td>
                                    <td class="px-2.5 py-1.5 text-right font-semibold">{{ number_format($total) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-2.5 py-2 text-center text-[11px] text-gray-500 dark:text-gray-400">Chưa có dữ liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Table: Danh sách chiến dịch --}}
        <div class="cs-table-block">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
