<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $currentAdmin = Auth::guard('admin')->user();

        $query = ActivityLog::with('admin')->orderBy('created_at', 'desc');

        // Jika bukan superadmin, hanya boleh melihat log miliknya sendiri
        if (!$currentAdmin->isSuperAdmin()) {
            $query->where('admin_id', $currentAdmin->id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter admin_id hanya boleh dipakai oleh superadmin
        if ($currentAdmin->isSuperAdmin() && $request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        $logs = $query->paginate(25)->withQueryString();

        // Daftar event: untuk admin biasa hanya dari log miliknya
        $eventsQuery = ActivityLog::query();
        if (!$currentAdmin->isSuperAdmin()) {
            $eventsQuery->where('admin_id', $currentAdmin->id);
        }
        $events = $eventsQuery->select('event')->distinct()->orderBy('event')->pluck('event');

        // Daftar admin hanya diperlukan untuk superadmin (filter)
        $admins = $currentAdmin->isSuperAdmin()
            ? Admin::orderBy('name')->get()
            : collect();

        return view('admin.logs.index', compact('logs', 'events', 'admins', 'currentAdmin'));
    }
}

