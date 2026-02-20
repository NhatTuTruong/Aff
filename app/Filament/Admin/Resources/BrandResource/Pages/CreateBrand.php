<?php

namespace App\Filament\Admin\Resources\BrandResource\Pages;

use App\Filament\Admin\Resources\BrandResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Lưu')
                ->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Lưu'),
            $this->getCancelFormAction(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
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
