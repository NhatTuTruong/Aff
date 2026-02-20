<?php

namespace App\Filament\Admin\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class FileManager extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static string $view = 'filament.admin.pages.file-manager';

    protected static ?string $navigationLabel = 'Quản lý tệp tin tải lên';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 5;

    public $currentDirectory = '';

    public $directories = [];

    public $files = [];

    public $allFiles = [];

    /** @var array<string> */
    public $selectedFiles = [];

    public $uploadedFiles = [];

    public string $dateFilter = 'all';

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    protected function getUserBasePath(): string
    {
        $userCode = Filament::auth()->user()?->code ?? '00000';

        return 'users/' . $userCode;
    }

    protected function isPathAllowed(string $path): bool
    {
        $base = $this->getUserBasePath();

        return $path === $base || str_starts_with($path . '/', $base . '/');
    }

    public function mount(): void
    {
        $this->loadDirectory($this->getUserBasePath());
    }

    public function loadDirectory(?string $directory = null): void
    {
        $basePath = $this->getUserBasePath();

        // Chỉ cho phép xem thư mục trong users/{user_code}/...
        if ($directory === null || $directory === '') {
            $directory = $basePath;
        }
        if (! $this->isPathAllowed($directory)) {
            $directory = $basePath;
        }

        $this->currentDirectory = $directory;
        $this->selectedFiles = [];
        $disk = Storage::disk('public');
        $path = $this->currentDirectory;

        if (! $disk->exists($path)) {
            $disk->makeDirectory($path, 0755, true);
        }

        // Get directories (chỉ trong phạm vi user)
        $this->directories = collect($disk->directories($path))
            ->filter(fn (string $dir) => $this->isPathAllowed($dir))
            ->map(function ($dir) {
                return [
                    'name' => basename($dir),
                    'path' => $dir,
                ];
            })
            ->values()
            ->toArray();

        // Get files
        $this->allFiles = collect($disk->files($path))
            ->map(function ($file) use ($disk) {
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'size' => $disk->size($file),
                    'mime' => $disk->mimeType($file),
                    'url' => $disk->url($file),
                    'last_modified' => $disk->lastModified($file),
                ];
            })
            ->toArray();

        $this->applyDateFilter();
    }

    public function applyDateFilter(): void
    {
        $now = time();
        $todayStart = strtotime('today 00:00:00');
        $weekStart = strtotime('-7 days 00:00:00');
        $monthStart = strtotime('-30 days 00:00:00');

        $this->files = collect($this->allFiles)->filter(function ($file) use ($now, $todayStart, $weekStart, $monthStart) {
            $ts = $file['last_modified'] ?? 0;
            return match ($this->dateFilter) {
                'today' => $ts >= $todayStart,
                'week' => $ts >= $weekStart,
                'month' => $ts >= $monthStart,
                'range' => $this->filterByDateRange($ts),
                default => true,
            };
        })->values()->toArray();
    }

    protected function filterByDateRange(int $timestamp): bool
    {
        if (! $this->dateFrom && ! $this->dateTo) {
            return true;
        }
        $ts = $timestamp;
        if ($this->dateFrom && $ts < strtotime($this->dateFrom . ' 00:00:00')) {
            return false;
        }
        if ($this->dateTo && $ts > strtotime($this->dateTo . ' 23:59:59')) {
            return false;
        }
        return true;
    }

    public function setDateFilter(string $filter): void
    {
        $this->dateFilter = $filter;
        $this->applyDateFilter();
    }

    public function updatedDateFrom(): void
    {
        $this->dateFilter = 'range';
        $this->applyDateFilter();
    }

    public function updatedDateTo(): void
    {
        $this->dateFilter = 'range';
        $this->applyDateFilter();
    }

    public function navigateToDirectory(string $directory): void
    {
        if ($this->isPathAllowed($directory)) {
            $this->loadDirectory($directory);
        }
    }

    public function goHome(): void
    {
        $this->loadDirectory($this->getUserBasePath());
    }

    public function goUp(): void
    {
        $basePath = $this->getUserBasePath();
        if ($this->currentDirectory === $basePath) {
            return;
        }
        $parent = dirname($this->currentDirectory);
        $this->loadDirectory($parent === '.' ? $basePath : $parent);
    }

    public function deleteFile(string $filePath): void
    {
        if (! $this->isPathAllowed($filePath)) {
            return;
        }
        Storage::disk('public')->delete($filePath);
        $this->loadDirectory($this->currentDirectory);
    }

    public function deleteSelectedFiles(): void
    {
        if (empty($this->selectedFiles)) {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('Chưa chọn tệp')
                ->body('Vui lòng chọn ít nhất một tệp tin để xóa.')
                ->send();

            return;
        }
        $disk = Storage::disk('public');
        $deleted = 0;
        foreach ($this->selectedFiles as $filePath) {
            if ($this->isPathAllowed($filePath) && $disk->exists($filePath)) {
                $disk->delete($filePath);
                $deleted++;
            }
        }
        $this->selectedFiles = [];
        $this->loadDirectory($this->currentDirectory);

        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Đã xóa')
            ->body("Đã xóa {$deleted} tệp tin.")
            ->send();
    }

    public function toggleSelectFile(string $filePath): void
    {
        if (! $this->isPathAllowed($filePath)) {
            return;
        }
        $key = array_search($filePath, $this->selectedFiles, true);
        if ($key !== false) {
            unset($this->selectedFiles[$key]);
            $this->selectedFiles = array_values($this->selectedFiles);
        } else {
            $this->selectedFiles[] = $filePath;
        }
    }

    public function deleteDirectory(string $directoryPath): void
    {
        Storage::disk('public')->deleteDirectory($directoryPath);
        $this->loadDirectory($this->currentDirectory);
    }

    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function isImage(string $mime): bool
    {
        return str_starts_with($mime, 'image/');
    }

    public function uploadFiles(): void
    {
        if (empty($this->uploadedFiles)) {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('Chưa chọn tệp')
                ->body('Vui lòng chọn ít nhất một tệp tin để tải lên.')
                ->send();
            return;
        }

        $this->validate([
            'uploadedFiles.*' => 'file|max:10240', // Max 10MB per file
        ]);

        $disk = Storage::disk('public');
        $path = $this->currentDirectory ?: '';

        foreach ($this->uploadedFiles as $file) {
            $disk->putFileAs($path, $file, $file->getClientOriginalName());
        }

        $this->uploadedFiles = [];
        $this->loadDirectory($this->currentDirectory);
        
        $this->dispatch('close-modal', id: 'upload-files');
        
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Tải lên thành công')
            ->body('Các tệp tin đã được tải lên.')
            ->send();
    }
}

