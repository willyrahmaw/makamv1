<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public static function log(string $event, ?Model $model = null, array $payload = [], ?Request $request = null): void
    {
        try {
            $req = $request ?? request();
            $adminId = Auth::guard('admin')->id();

            ActivityLog::create([
                'admin_id' => $adminId,
                'event' => $event,
                'model_type' => $model ? $model::class : null,
                'model_id' => $model?->getKey(),
                'route' => optional($req->route())->getName(),
                'method' => $req->method(),
                'ip' => $req->ip(),
                'user_agent' => substr((string) $req->userAgent(), 0, 1000),
                'payload' => $payload ?: null,
            ]);
        } catch (\Throwable $e) {
            // Jangan menggagalkan proses utama kalau logging gagal.
        }
    }
}

