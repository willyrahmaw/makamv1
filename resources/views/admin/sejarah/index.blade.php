@extends('layouts.admin')

@section('title', 'Sejarah')
@section('page-title', 'Kelola Sejarah Desa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-book me-2"></i>Daftar Sejarah</span>
        <a href="{{ route('admin.sejarah.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Sejarah
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Konten</th>
                        <th>Narasumber</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sejarah as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 300px;">
                                {{ Str::limit(strip_tags($s->konten), 100) }}
                            </div>
                        </td>
                        <td>
                            @if($s->narasumber_nama)
                                <strong>{{ $s->narasumber_nama }}</strong>
                                @if($s->narasumber_jabatan)
                                    <br><small class="text-muted">{{ $s->narasumber_jabatan }}</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($s->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>{{ $s->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.sejarah.edit', $s) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(Auth::guard('admin')->user()?->isSuperAdmin())
                                <form action="{{ route('admin.sejarah.destroy', $s) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus" data-message="Yakin ingin menghapus sejarah ini?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-book" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Belum ada sejarah</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
