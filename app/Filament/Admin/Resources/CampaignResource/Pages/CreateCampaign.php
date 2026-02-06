<?php

namespace App\Filament\Admin\Resources\CampaignResource\Pages;

use App\Filament\Admin\Resources\CampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Lưu')
                ->color('primary')
                ->action('create'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $landingUrl = route('landing.show', $this->record->slug);
        $fullUrl = url($landingUrl);
        
        Notification::make()
            ->title('Campaign created successfully! 🎉')
            ->body("Your landing page is ready!\n\nURL: {$fullUrl}\n\nClick 'Copy URL' to copy it for Google Ads.")
            ->success()
            ->persistent()
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('View')
                    ->url($landingUrl, shouldOpenInNewTab: true)
                    ->button(),
                \Filament\Notifications\Actions\Action::make('copy')
                    ->label('Copy URL')
                    ->button()
                    ->action(function () use ($fullUrl) {
                        // Dispatch event to copy URL
                        $this->dispatch('copy-url', url: $fullUrl);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('URL copied to clipboard!')
                            ->success()
                            ->send();
                    }),
            ])
            ->send();
    }
}
