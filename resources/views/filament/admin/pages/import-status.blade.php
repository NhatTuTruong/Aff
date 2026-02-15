<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Theo dõi tiến trình import CSV. Bảng tự động cập nhật mỗi 3 giây khi có import đang xử lý.
        </p>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
