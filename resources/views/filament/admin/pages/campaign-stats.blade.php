<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Danh sách chiến dịch đang hoạt động, sắp xếp theo số lượt click từ cao xuống thấp. Nhấn "Xem biểu đồ" để xem thống kê chi tiết theo thời gian.
        </p>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
