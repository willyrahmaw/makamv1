@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Admin')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-activity me-2"></i>Filter Log</span>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Aktivitas</label>
                <select name="event" class="form-select">
                    <option value="">Semua</option>
                    @foreach($events as $event)
                        <option value="{{ $event }}" {{ request('event') === $event ? 'selected' : '' }}>
                            {{ \App\Models\ActivityLog::eventLabels()[$event] ?? $event }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($currentAdmin->isSuperAdmin())
            <div class="col-md-4">
                <label class="form-label">Admin</label>
                <select name="admin_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ (string)request('admin_id') === (string)$admin->id ? 'selected' : '' }}>
                            {{ $admin->name }} ({{ $admin->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter me-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
            @else
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-filter me-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-journal-text me-2"></i>Log Aktivitas Terbaru
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width:1%;">#</th>
                        <th>Waktu</th>
                        <th>Admin</th>
                        <th>Aktivitas</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $loop->index }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($log->admin)
                                    <strong>{{ $log->admin->name }}</strong><br>
                                    <small class="text-muted">{{ $log->admin->email }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $log->event_label }}</span></td>
                            <td>
                                <small class="text-muted">{{ $log->ip ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-activity" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada log aktivitas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
