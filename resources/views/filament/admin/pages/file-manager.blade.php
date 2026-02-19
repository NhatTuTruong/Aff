<x-filament-panels::page>
    <div class="space-y-6 w-full max-w-full overflow-x-auto">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <a wire:click="loadDirectory(null)" class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-100">
                Home
            </a>
            @if($currentDirectory)
                @php
                    $segments = explode('/', $currentDirectory);
                    // Bỏ qua segment "users" và hiển thị từ user_code trở đi
                    $displaySegments = array_slice($segments, 2);
                @endphp
                @foreach($displaySegments as $index => $segment)
                    @if($segment)
                        <span>/</span>
                        @php
                            $pathUpToHere = 'users/' . implode('/', array_slice($segments, 1, 2 + $index));
                        @endphp
                        <a wire:click="loadDirectory('{{ $pathUpToHere }}')" 
                           class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-100">
                            {{ $segment }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- Date filter (when inside folder) -->
        @if($currentDirectory)
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex gap-2">
                    <x-filament::button
                        wire:click="setDateFilter('all')"
                        size="sm"
                        :color="$dateFilter === 'all' ? 'primary' : 'gray'"
                        variant="{{ $dateFilter === 'all' ? 'filled' : 'outlined' }}">
                        Tất cả
                    </x-filament::button>
                    <x-filament::button
                        wire:click="setDateFilter('today')"
                        size="sm"
                        :color="$dateFilter === 'today' ? 'primary' : 'gray'"
                        variant="{{ $dateFilter === 'today' ? 'filled' : 'outlined' }}">
                        Hôm nay
                    </x-filament::button>
                    <x-filament::button
                        wire:click="setDateFilter('week')"
                        size="sm"
                        :color="$dateFilter === 'week' ? 'primary' : 'gray'"
                        variant="{{ $dateFilter === 'week' ? 'filled' : 'outlined' }}">
                        Tuần này
                    </x-filament::button>
                    <x-filament::button
                        wire:click="setDateFilter('month')"
                        size="sm"
                        :color="$dateFilter === 'month' ? 'primary' : 'gray'"
                        variant="{{ $dateFilter === 'month' ? 'filled' : 'outlined' }}">
                        Tháng này
                    </x-filament::button>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="dateFrom" class="fi-input block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                    <span class="text-gray-500">–</span>
                    <input type="date" wire:model.live="dateTo" class="fi-input block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex items-center justify-between">
            @if($currentDirectory)
                <x-filament::button wire:click="goUp" icon="heroicon-o-arrow-left">
                    Quay lại
                </x-filament::button>
            @else
                <div></div>
            @endif
            
            <x-filament::button 
                x-data=""
                x-on:click="$dispatch('open-modal', { id: 'upload-files' })"
                icon="heroicon-o-arrow-up-tray">
                Tải lên tệp tin
            </x-filament::button>
        </div>

        <!-- Directories -->
        @if(count($directories) > 0)
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Thư mục</h3>
                <div style="display:grid; grid-template-columns: repeat(8, minmax(0, 1fr)); gap:0.75rem; width:100%;">
                    @foreach($directories as $directory)
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2 hover:shadow-md transition-shadow cursor-pointer flex flex-col items-center text-center"
                             wire:click="navigateToDirectory('{{ $directory['path'] }}')">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-2 flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-900 dark:text-gray-100 truncate w-full">{{ $directory['name'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Files -->
        @if(count($files) > 0)
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tệp tin</h3>
                <div style="display:grid; grid-template-columns: repeat(8, minmax(0, 1fr)); gap:0.75rem; width:100%;">
                    @foreach($files as $file)
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-2 hover:shadow-md transition-shadow">
                            @if($this->isImage($file['mime']))
                                <div class="aspect-square mb-2 rounded overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @else
                                <div class="aspect-square mb-2 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <p class="text-xs font-medium text-gray-900 dark:text-gray-100 truncate mb-1" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span>{{ $this->formatBytes($file['size']) }}</span>
                                <span>{{ date('d/m/Y', $file['last_modified']) }}</span>
                            </div>
                            <div class="flex gap-1">
                                <x-filament::icon-button size="sm" color="gray" tag="a" href="{{ $file['url'] }}" target="_blank" icon="heroicon-o-eye" tooltip="Xem" />
                                <x-filament::icon-button size="sm" color="danger" wire:click="deleteFile('{{ $file['path'] }}')" wire:confirm="Bạn có chắc muốn xóa tệp tin này?" icon="heroicon-o-trash" tooltip="Xóa" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(count($allFiles) > 0 && $currentDirectory)
            <div class="text-center py-12">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Không có tệp tin phù hợp với bộ lọc</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Thử chọn bộ lọc khác hoặc "Tất cả".</p>
            </div>
        @elseif(count($directories) === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Không có tệp tin</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Thư mục này đang trống.</p>
            </div>
        @endif
    </div>

    <!-- Upload Modal -->
    <x-filament::modal id="upload-files" heading="Tải lên tệp tin" width="md">
        <div class="space-y-4">
            <div>
                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Chọn tệp tin</span>
                </label>
                <input type="file"
                       wire:model="uploadedFiles"
                       multiple
                       accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx"
                       class="mt-2 block w-full text-sm text-gray-500 file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-500/10 dark:file:text-primary-400 dark:hover:file:bg-primary-500/20 file:cursor-pointer">
                @error('uploadedFiles.*')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            @if(count($uploadedFiles) > 0)
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Đã chọn {{ count($uploadedFiles) }} tệp tin
                </div>
            @endif

            <div wire:loading wire:target="uploadedFiles" class="text-sm text-gray-500">
                Đang xử lý...
            </div>
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="uploadFiles" :disabled="count($uploadedFiles) === 0">
                Tải lên
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>
