<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockPublicIpsMiddleware
{
    /**
     * Chặn IP có block_public=true truy cập các trang public.
     * Cho phép truy cập /admin (trang quản trị).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*')) {
            return $next($request);
        }

        $ip = $request->ip();
        if (BlockedIp::isBlockedFromPublic($ip)) {
            abort(403, 'Quyền truy cập bị từ chối.');
        }

        return $next($request);
    }
}
