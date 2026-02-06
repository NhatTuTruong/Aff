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
        // Use intended() to respect any intended URL, fallback to dashboard
        // This matches Filament's default behavior
        return redirect()->intended(Filament::getUrl());
    }
}

