<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForbidDeleteForAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        // Superadmin boleh hapus
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Admin tidak diizinkan menghapus data.');
    }
}

