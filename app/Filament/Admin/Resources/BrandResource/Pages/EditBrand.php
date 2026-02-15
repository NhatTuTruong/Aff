<?php

namespace App\Filament\Admin\Resources\BrandResource\Pages;

use App\Filament\Admin\Resources\BrandResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Lưu')
                ->formId('form'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Lưu'),
            $this->getCancelFormAction(),
        ];
    }

    /** Chọn ảnh từ popup "Chọn từ ảnh đã có" → cập nhật Logo / Hình ảnh cùng cơ chế như upload từ máy (state dạng [uuid => path]). */
    public function selectLogoAndClose(string $path): void
    {
        $path = str_replace('\\', '/', ltrim($path, '/'));
        if (!str_starts_with($path, 'brands/')) {
            $path = 'brands/' . ltrim($path, '/');
        }
        $this->data['image'] = [\Illuminate\Support\Str::uuid()->toString() => $path];
    }
}
