<x-filament-panels::page>
    <style>
        .cs-page {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .cs-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
            justify-content: space-between;
        }
        .cs-filters-right {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }
        .cs-summary-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 768px) {
            .cs-summary-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        .cs-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.5fr) minmax(0, 1.2fr);
            gap: 16px;
            align-items: flex-start;
        }
        @media (max-width: 1023px) {
            .cs-main-grid {
                grid-template-columns: 1fr;
            }
        }
        .cs-side-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 640px) {
            .cs-side-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .cs-table-num {
            width: 1%;
            white-space: nowrap;
            text-align: right;
        }
        .cs-top-table-scroll {
            max-height: 388px;
            overflow-y: auto;
        }
        .cs-top-table {
            margin: 0px auto !important;
        }
    </style>
    <div class="cs-page">
        <div class="cs-filters">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Thống kê hiệu suất chiến dịch theo Clicks / Views / CTR. Bạn có thể lọc theo khoảng thời gian, trạng thái và cửa hàng.
                </p>
            </div>
            <div class="cs-filters-right text-sm">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Thời gian</label>
                    <select wire:model="periodInput" class="mt-1 block rounded-md border-gray-300 text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900">
                        <option value="7">7 ngày</option>
                        <option value="30">30 ngày</option>
                        <option value="90">90 ngày</option>
                        <option value="all">Tất cả</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái</label>
                    <select wire:model="filterStatusInput" class="mt-1 block rounded-md border-gray-300 text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900">
                        <option value="active">Đang hoạt động</option>
                        <option value="draft">Bản nháp</option>
                        <option value="paused">Tạm dừng</option>
                        <option value="all">Tất cả</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Cửa hàng</label>
                    <select wire:model="filterBrandIdInput" class="mt-1 block rounded-md border-gray-300 text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">Tất cả</option>
                        @foreach($this->brandOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Danh mục</label>
                    <select wire:model="filterCategoryIdInput" class="mt-1 block rounded-md border-gray-300 text-xs shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900">
                        <option value="">Tất cả</option>
                        @foreach($this->categoryOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="button"
                        wire:click="applyFilters"
                        class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-400">
                        Lọc
                    </button>
                </div>
            </div>
        </div>

        @php($stats = $this->summaryStats)

        <div class="cs-summary-grid">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
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
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
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
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">CTR</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ number_format($stats['totals']['ctr'] ?? 0, 2) }}%
                </div>
                <div class="mt-1 text-xs {{ ($stats['totals']['ctr_change'] ?? 0) >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ ($stats['totals']['ctr_change'] ?? 0) >= 0 ? '▲' : '▼' }}
                    {{ number_format(abs($stats['totals']['ctr_change'] ?? 0), 2) }} điểm so với kỳ trước
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Cảnh báo</div>
                <ul class="mt-1 space-y-1 text-xs text-gray-600 dark:text-gray-300">
                    <li>Thiếu logo: <strong>{{ $stats['alerts']['missing_logo'] ?? 0 }}</strong></li>
                    <li>Thiếu giới thiệu: <strong>{{ $stats['alerts']['missing_intro'] ?? 0 }}</strong></li>
                    <li>Thiếu danh mục: <strong>{{ $stats['alerts']['missing_category'] ?? 0 }}</strong></li>
                    <li>Thiếu affiliate URL: <strong>{{ $stats['alerts']['missing_affiliate'] ?? 0 }}</strong></li>
                </ul>
            </div>
        </div>

        <div class="cs-main-grid">
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Top chiến dịch theo Clicks</h3>
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="cs-top-table-scroll">
                    <table class="cs-top-table min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Chiến dịch</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Clicks</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                                <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">CTR</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($stats['topCampaigns'] as $c)
                                <tr>
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $c['title'] }}</div>
                                        @if(!empty($c['brand']))
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $c['brand'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">{{ number_format($c['clicks']) }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($c['views']) }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($c['ctr'], 2) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-3 text-center text-xs text-gray-500 dark:text-gray-400">
                                        Chưa có dữ liệu trong khoảng thời gian này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="cs-side-grid">
                <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-100">Top cửa hàng theo Clicks</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-2 py-1 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Cửa hàng</th>
                                <th class="px-2 py-1 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Clicks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($stats['topBrands'] as $b)
                                <tr>
                                    <td class="px-2 py-1 truncate">{{ $b['name'] }}</td>
                                    <td class="px-2 py-1 text-right font-semibold">{{ number_format($b['clicks']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-2 py-2 text-center text-[11px] text-gray-500 dark:text-gray-400">
                                        Chưa có dữ liệu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-100">Chất lượng Coupon</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-2 py-1 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Loại</th>
                                <th class="px-2 py-1 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <tr>
                                <td class="px-2 py-1">Có code</td>
                                <td class="px-2 py-1 text-right font-semibold">{{ $stats['couponQuality']['with_code'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1">Chỉ deal (không code)</td>
                                <td class="px-2 py-1 text-right font-semibold">{{ $stats['couponQuality']['without_code'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1">Thiếu offer/mô tả</td>
                                <td class="px-2 py-1 text-right font-semibold">{{ $stats['couponQuality']['missing_text'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="px-2 py-1">Sắp hết hạn (7 ngày)</td>
                                <td class="px-2 py-1 text-right font-semibold">{{ $stats['couponQuality']['soon_expiring'] ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-100">Thiết bị</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-2 py-1 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Thiết bị</th>
                                <th class="px-2 py-1 cs-table-num font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($stats['devices'] as $device => $total)
                                <tr>
                                    <td class="px-2 py-1">{{ ucfirst($device) }}</td>
                                    <td class="px-2 py-1 text-right font-semibold">{{ number_format($total) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-2 py-2 text-center text-[11px] text-gray-500 dark:text-gray-400">
                                        Chưa có dữ liệu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-3 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-100">Top nguồn truy cập</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-2 py-1 text-left font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Referrer</th>
                                <th class="px-2 py-1 text-right font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($stats['referrers'] as $ref)
                                <tr>
                                    <td class="px-2 py-1">
                                        <span class="truncate block max-w-[150px]" title="{{ $ref['referer'] }}">{{ $ref['referer'] }}</span>
                                    </td>
                                    <td class="px-2 py-1 cs-table-num font-semibold">{{ number_format($ref['total']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-2 py-2 text-center text-[11px] text-gray-500 dark:text-gray-400">
                                        Chưa có dữ liệu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
