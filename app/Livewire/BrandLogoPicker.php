<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BrandLogoPicker extends Component
{
    public array $images = [];

    public bool $showModal = false;

    public string $filter = 'all';

    public ?string $selectedPath = null;

    public function openLibrary(): void
    {
        $this->loadImages();
        $this->showModal = true;
        $this->selectedPath = null;
        $this->filter = 'all';
    }

    public function refreshImages(): void
    {
        $this->loadImages();
    }

    public function loadImages(): void
    {
        $user = Filament::auth()->user();
        $files = [];

        // 1. Load từ thư mục users/{user_code}/brands (ảnh từ "Lấy logo" và FileManager)
        if ($user) {
            $userCode = $user->code ?? '00000';
            $userBrandsPath = "users/{$userCode}/brands";
            if (Storage::disk('public')->exists($userBrandsPath)) {
                $files = array_merge(
                    $files,
                    Storage::disk('public')->allFiles($userBrandsPath)
                );
            }
        }

        // 2. Load từ thư mục brands/ (FileUpload và legacy)
        if (Storage::disk('public')->exists('brands')) {
            $brandFiles = Storage::disk('public')->allFiles('brands');
            // Nếu multi-tenant: chỉ lấy ảnh của brands thuộc user
            if ($user) {
                $userBrandImages = \App\Models\Brand::where('user_id', $user->id)
                    ->pluck('image')
                    ->filter()
                    ->map(fn ($img) => str_replace('\\', '/', ltrim((string) $img, '/')))
                    ->toArray();
                $brandFiles = array_filter($brandFiles, function ($file) use ($userBrandImages) {
                    $normalized = str_replace('\\', '/', $file);
                    return in_array($normalized, $userBrandImages);
                });
            }
            $files = array_merge($files, $brandFiles);
        }

        $this->images = collect($files)
            ->unique()
            ->filter(fn ($f) => preg_match('/\.(jpe?g|png|gif|webp|ico)$/i', $f))
            ->map(fn ($f) => [
                'path' => str_replace('\\', '/', $f),
                'url' => Storage::disk('public')->url($f),
                'lastModified' => Storage::disk('public')->lastModified($f) ?: 0,
            ])
            ->sortByDesc('lastModified')
            ->values()
            ->all();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function selectImage(string $path): void
    {
        $path = str_replace('\\', '/', ltrim($path, '/'));
        // Giữ nguyên path vì đã được lọc theo user rồi
        $this->dispatch('logo-selected', path: $path);
        $this->closeModal();
    }

    public function getFilteredImagesProperty(): array
    {
        if ($this->filter === 'all') {
            return $this->images;
        }
        $todayStart = (int) strtotime('today 00:00:00');
        $weekAgo = $todayStart - (7 * 24 * 60 * 60);
        return collect($this->images)->filter(function ($item) use ($todayStart, $weekAgo) {
            $ts = $item['lastModified'] ?? 0;
            return match ($this->filter) {
                'today' => $ts >= $todayStart,
                '7days' => $ts >= $weekAgo,
                default => true,
            };
        })->values()->all();
    }

    public function render()
    {
        return view('livewire.brand-logo-picker');
    }
}
