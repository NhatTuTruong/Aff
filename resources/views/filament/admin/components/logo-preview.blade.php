@php
    $previewPath = $previewPath ?? null;
    $previewUrl = $previewPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($previewPath) : null;
@endphp

@if($previewUrl)
<div class="fi-fo-field-wrp-logo-preview p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
            <img src="{{ $previewUrl }}" 
                 alt="Logo preview" 
                 class="w-24 h-24 object-contain rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 p-2">
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo đã tải về</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Nhấn nút "Upload" ở trên để thêm logo vào form</p>
        </div>
    </div>
</div>
@endif

