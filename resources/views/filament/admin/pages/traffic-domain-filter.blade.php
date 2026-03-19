<x-filament-panels::page>
    <div class="space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Nhập token Apify, upload file <code>.txt</code> (mỗi dòng 1 domain), sau đó bấm <strong>Chạy &amp; tải CSV</strong>.
        </p>

        {{ $this->form }}

        <div class="space-y-2">
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Log quá trình chạy
            </h2>
            <div class="bg-gray-900 text-gray-100 text-xs rounded-lg p-3 max-h-64 overflow-y-auto">
                @if(empty($this->logs))
                    <div class="text-gray-400">Chưa có log.</div>
                @else
                    @foreach($this->logs as $line)
                        <div>{{ $line }}</div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>

