@extends('layouts.admin')

@section('title', 'Admin')
@section('page-title', 'Kelola Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people-fill me-2"></i>Daftar Admin</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Admin
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admins->firstItem() + $loop->index }}</td>
                        <td class="fw-medium">{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $admin) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth('admin')->id() !== $admin->id)
                                <form action="{{ route('admin.users.destroy', $admin) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete-confirm"
                                        title="Hapus"
                                        data-message="Yakin ingin menghapus admin {{ $admin->name }}?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Belum ada admin</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="mt-3">
            {{ $admins->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

