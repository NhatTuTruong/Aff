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
            Actions\Action::make('view_landing')
                ->label('')
                ->icon('heroicon-o-eye')
                ->tooltip('Xem trang landing page')
                ->url(function () {
                    $parts = explode('/', $this->record->slug, 2);
                    if (count($parts) === 2) {
                        return route('landing.show', ['userCode' => $parts[0], 'slug' => $parts[1]]);
                    }
                    return route('landing.show', ['userCode' => '00000', 'slug' => $this->record->slug]);
                })
                ->openUrlInNewTab(),
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
