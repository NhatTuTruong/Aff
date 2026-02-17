<?php

namespace App\Livewire;

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

    public function loadImages(): void
    {
        $files = Storage::disk('public')->exists('brands')
            ? Storage::disk('public')->files('brands')
            : [];
        $this->images = collect($files)
            ->filter(fn ($f) => preg_match('/\.(jpe?g|png|gif|webp|ico)$/i', $f))
            ->map(fn ($f) => [
                'path' => $f,
                'url' => Storage::disk('public')->url($f),
                'lastModified' => Storage::disk('public')->lastModified($f),
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
        if (! str_starts_with($path, 'brands/')) {
            $path = 'brands/' . ltrim($path, '/');
        }
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
