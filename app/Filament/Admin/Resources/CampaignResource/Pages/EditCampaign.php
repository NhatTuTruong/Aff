<?php

namespace App\Filament\Admin\Resources\CampaignResource\Pages;

use App\Filament\Admin\Resources\CampaignResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Lưu')
                ->formId('form'),
            Actions\Action::make('copy_url')
                ->label('Copy Landing URL')
                ->icon('heroicon-o-clipboard')
                ->action(function () {
                    $url = url(route('landing.show', $this->record->slug));
                    // Copy to clipboard using Livewire dispatch
                    $this->dispatch('copy-url', url: $url);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('URL copied to clipboard!')
                        ->body($url)
                        ->success()
                        ->send();
                }),
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
}
