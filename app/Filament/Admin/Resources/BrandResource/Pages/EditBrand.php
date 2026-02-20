<?php

namespace App\Filament\Admin\Resources\BrandResource\Pages;

use App\Filament\Admin\Resources\BrandResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

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

    /** Chọn ảnh từ popup Livewire "Chọn từ thư viện" → cập nhật Logo / Hình ảnh (merge, không xóa field khác). */
    #[On('logo-selected')]
    public function selectLogoAndClose(string $path): void
    {
        $path = str_replace('\\', '/', ltrim((string) $path, '/'));
        $state = array_merge($this->form->getRawState(), ['image' => $path]);
        $this->form->fill($state);
    }
}
