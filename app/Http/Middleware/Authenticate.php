<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Redirect to Filament login if Filament is available
        if (Filament::getCurrentPanel()) {
            return Filament::getLoginUrl();
        }

        // Fallback to traditional login route if Filament is not available
        return route('login');
    }
}

