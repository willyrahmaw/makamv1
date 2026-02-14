<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Hanya superadmin yang diizinkan.');
        }

        return $next($request);
    }
}

