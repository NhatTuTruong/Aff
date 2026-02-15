<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b dark:border-gray-600">
                <th class="px-4 py-2 text-left font-medium">#</th>
                <th class="px-4 py-2 text-left font-medium">Lỗi</th>
                <th class="px-4 py-2 text-left font-medium">Dữ liệu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($failedRows as $index => $row)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 text-danger-600 dark:text-danger-400 max-w-xs truncate" title="{{ $row->validation_error }}">
                        {{ $row->validation_error ?? 'Lỗi không xác định' }}
                    </td>
                    <td class="px-4 py-2 text-gray-600 dark:text-gray-400 max-w-md">
                        <pre class="text-xs whitespace-pre-wrap break-all">{{ json_encode($row->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
