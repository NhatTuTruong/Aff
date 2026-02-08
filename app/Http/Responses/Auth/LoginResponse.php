<?php

namespace App\Http\Responses\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        // Redirect to dashboard after successful login
        $url = Filament::getUrl();
        
        // Use to() instead of intended() for better Livewire compatibility
        return redirect()->to($url);
    }
}

