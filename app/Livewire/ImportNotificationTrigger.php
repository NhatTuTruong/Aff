<?php

namespace App\Livewire;

use Livewire\Component;

class ImportNotificationTrigger extends Component
{
    public function checkAndDispatchNotifications(): void
    {
        if (count(session()->get('filament.notifications') ?? []) > 0) {
            $this->dispatch('notificationsSent');
        }
    }

    public function render()
    {
        return view('livewire.import-notification-trigger');
    }
}
