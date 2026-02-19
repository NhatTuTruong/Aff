<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Filament\Facades\Filament;

class TestAuthController extends Controller
{
    public function testAuth(Request $request)
    {
        $results = [];
        
        // Test 1: Check if user is authenticated
        $results['auth_check'] = Auth::check();
        $results['auth_user'] = Auth::user() ? Auth::user()->email : null;
        $results['auth_id'] = Auth::id();
        
        // Test 2: Check session
        $results['session_id'] = Session::getId();
        $results['session_all'] = Session::all();
        $results['session_has_auth'] = Session::has('login_web_' . sha1('web'));
        
        // Test 3: Check Filament panel
        $results['filament_panel'] = Filament::getCurrentPanel() ? Filament::getCurrentPanel()->getId() : null;
        
        // Test 4: Check canAccessPanel
        $user = Auth::user();
        if ($user) {
            $panel = Filament::getCurrentPanel();
            if ($panel) {
                $results['can_access_panel'] = $user->canAccessPanel($panel);
            }
        }
        
        // Test 5: Check guard
        $results['guard_check'] = Auth::guard('web')->check();
        $results['guard_user'] = Auth::guard('web')->user() ? Auth::guard('web')->user()->email : null;
        
        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    }
}
