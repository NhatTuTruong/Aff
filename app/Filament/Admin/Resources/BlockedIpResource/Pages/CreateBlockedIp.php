<?php

namespace App\Filament\Admin\Resources\BlockedIpResource\Pages;

use App\Filament\Admin\Resources\BlockedIpResource;
use App\Models\BlockedIp;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedIp extends CreateRecord
{
    protected static string $resource = BlockedIpResource::class;

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        $count = BlockedIp::query()->forUser($user?->id, $isAdmin)->count();
        $max = config('blocked_ips.max_count', 500);

        if ($count >= $max) {
            $this->redirect(BlockedIpResource::getUrl('index'));
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if (! (auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())) {
            $data['block_public'] = false;
        }

        return $data;
    }
}
