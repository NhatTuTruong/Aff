<?php

namespace App\Filament\Admin\Resources\CampaignResource\Pages;

use App\Filament\Admin\Resources\CampaignResource;
use App\Models\Brand;
use App\Services\GeminiCampaignReviewService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['intro']) || trim(strip_tags((string) $data['intro'])) === '') {
            $brand = ! empty($data['brand_id']) ? Brand::find($data['brand_id']) : null;
            $brandName = $brand?->name ?: (string) ($data['title'] ?? '');
            $domain = $brand?->domain ?: $this->domainFromUrl((string) ($data['affiliate_url'] ?? ''));
            $campaignTitle = (string) ($data['title'] ?? '');

            $error = null;
            $html = app(GeminiCampaignReviewService::class)->generateIntroHtml($brandName, $domain, $campaignTitle, $error);
            if (! empty($html)) {
                $data['intro'] = $html;
            } elseif (! empty($error)) {
                // Store for afterCreate() notification.
                $data['_ai_intro_error'] = $error;
            }
        }

        return $data;
    }

    private function domainFromUrl(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (! is_string($host) || trim($host) === '') {
            return null;
        }
        $host = preg_replace('/^www\./i', '', trim($host));
        return $host !== '' ? $host : null;
    }

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterCreate(): void
    {
        // If AI generation failed (or key not loaded), tell the user why intro is still empty.
        if (empty($this->record->intro) || trim(strip_tags((string) $this->record->intro)) === '') {
            $error = $this->data['_ai_intro_error'] ?? null;

            Notification::make()
                ->title('Intro was not generated')
                ->body(is_string($error) && trim($error) !== ''
                    ? $error
                    : "Gemini request failed or timed out. Please try saving again, and ensure the server was restarted after updating .env.")
                ->warning()
                ->send();
        }

        $parts = explode('/', $this->record->slug, 2);
        if (count($parts) === 2) {
            $landingUrl = route('landing.show', ['userCode' => $parts[0], 'slug' => $parts[1]]);
        } else {
            $landingUrl = route('landing.show', ['userCode' => '00000', 'slug' => $this->record->slug]);
        }
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
