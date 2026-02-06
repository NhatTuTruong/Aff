<?php

namespace App\Filament\Admin\Pages;

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
    public $selectedFiles = [];
    public $uploadedFiles = [];

    public function mount(): void
    {
        $this->loadDirectory();
    }

    public function loadDirectory(?string $directory = null): void
    {
        $this->currentDirectory = $directory ?? '';
        $disk = Storage::disk('public');
        
        $path = $this->currentDirectory ?: '';
        
        // Get directories
        $this->directories = collect($disk->directories($path))
            ->map(function ($dir) {
                return [
                    'name' => basename($dir),
                    'path' => $dir,
                ];
            })
            ->toArray();
        
        // Get files
        $this->files = collect($disk->files($path))
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
    }

    public function navigateToDirectory(string $directory): void
    {
        $this->loadDirectory($directory);
    }

    public function goUp(): void
    {
        if (empty($this->currentDirectory)) {
            return;
        }
        
        $parent = dirname($this->currentDirectory);
        $this->loadDirectory($parent === '.' ? '' : $parent);
    }

    public function deleteFile(string $filePath): void
    {
        Storage::disk('public')->delete($filePath);
        $this->loadDirectory($this->currentDirectory);
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

