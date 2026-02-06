<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <a wire:click="loadDirectory('')" class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-100">
                Home
            </a>
            @if($currentDirectory)
                @foreach(explode('/', $currentDirectory) as $segment)
                    @if($segment)
                        <span>/</span>
                        <a wire:click="loadDirectory('{{ implode('/', array_slice(explode('/', $currentDirectory), 0, array_search($segment, explode('/', $currentDirectory)) + 1)) }}')" 
                           class="cursor-pointer hover:text-gray-900 dark:hover:text-gray-100">
                            {{ $segment }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>

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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($directories as $directory)
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer"
                             wire:click="navigateToDirectory('{{ $directory['path'] }}')">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $directory['name'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Files -->
        @if(count($files) > 0)
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Tệp tin</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($files as $file)
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
                            @if($this->isImage($file['mime']))
                                <div class="aspect-video mb-3 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="aspect-video mb-3 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $this->formatBytes($file['size']) }}</span>
                                    <span>{{ date('d/m/Y', $file['last_modified']) }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <x-filament::button 
                                        size="sm"
                                        color="gray"
                                        tag="a"
                                        href="{{ $file['url'] }}"
                                        target="_blank"
                                        icon="heroicon-o-eye">
                                        Xem
                                    </x-filament::button>
                                    <x-filament::button 
                                        size="sm"
                                        color="danger"
                                        wire:click="deleteFile('{{ $file['path'] }}')"
                                        wire:confirm="Bạn có chắc muốn xóa tệp tin này?"
                                        icon="heroicon-o-trash">
                                        Xóa
                                    </x-filament::button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
    <x-filament::modal id="upload-files" heading="Tải lên tệp tin">
        <form wire:submit.prevent="uploadFiles" class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input 
                    type="file" 
                    wire:model="uploadedFiles" 
                    multiple 
                    accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" />
            </x-filament::input.wrapper>
            
            @if(count($uploadedFiles) > 0)
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Đã chọn {{ count($uploadedFiles) }} tệp tin
                </div>
            @endif
            
            <x-slot name="footer">
                <x-filament::button type="submit">
                    Tải lên
                </x-filament::button>
            </x-slot>
        </form>
    </x-filament::modal>
</x-filament-panels::page>

