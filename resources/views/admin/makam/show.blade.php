@extends('layouts.admin')

@section('title', 'Detail Makam')
@section('page-title', 'Detail Data Makam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person me-2"></i>{{ $makam->nama_lengkap_bin_binti }}</span>
                <span class="badge bg-{{ $makam->jenis_kelamin == 'laki-laki' ? 'primary' : 'danger' }}">
                    {{ ucfirst($makam->jenis_kelamin) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @if($makam->foto)
                            <img src="{{ Storage::url($makam->foto) }}" class="img-fluid rounded" alt="{{ $makam->nama_lengkap ?? 'Makam' }}" style="max-width: 100%; height: auto;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-person" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="text-muted">Nama Lengkap</td>
                                <td class="fw-medium">{{ $makam->nama_lengkap_bin_binti }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Lahir</td>
                                <td>{{ $makam->tanggal_lahir ? $makam->tanggal_lahir->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Wafat</td>
                                <td>{{ $makam->tanggal_wafat?->format('d F Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Usia</td>
                                <td>{{ $makam->usia ? $makam->usia . ' tahun' : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Lokasi</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $makam->lokasi_makam }}</span>
                                </td>
                            </tr>
                            @if($makam->catatan)
                            <tr>
                                <td class="text-muted">Catatan</td>
                                <td>
                                    <div class="alert alert-info mb-0 py-2">
                                        <i class="bi bi-info-circle me-2"></i>{{ $makam->catatan }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Ahli Waris</td>
                                <td>{{ $makam->ahli_waris ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telepon</td>
                                <td>{{ $makam->telepon_ahli_waris ?? '-' }}</td>
                            </tr>
                            @if($makam->keterangan)
                            <tr>
                                <td class="text-muted">Keterangan</td>
                                <td>{{ $makam->keterangan }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.makam.edit', $makam) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.makam.destroy', $makam) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-delete-confirm" data-message="Yakin ingin menghapus data makam ini?">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('admin.makam.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
