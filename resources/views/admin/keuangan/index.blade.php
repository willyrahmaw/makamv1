@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-arrow-down-circle-fill"></i>
            </div>
            <div class="stat-info">
                <p>Pemasukan</p>
                <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </div>
            <div class="stat-info">
                <p>Pengeluaran</p>
                <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-info">
                <p>Saldo</p>
                <h3 class="{{ $saldo < 0 ? 'text-danger' : 'text-success' }}">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-funnel me-2"></i>Filter Laporan</span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.keuangan.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </a>
            <a href="{{ route('admin.keuangan.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-info btn-sm">
                <i class="bi bi-filetype-csv me-1"></i>Export CSV
            </a>
            <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Transaksi
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="text" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control" placeholder="dd/mm/yyyy" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="text" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control" placeholder="dd/mm/yyyy" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="tipe_filter" class="form-label">Tipe</label>
                <select name="tipe" id="tipe_filter" class="form-select">
                    <option value="">Semua</option>
                    <option value="pemasukan" {{ request('tipe') === 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('tipe') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cari (sumber/donatur/deskripsi)</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Donasi, operasional, dll">
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
                    Reset
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-graph-up me-2"></i>Trend Keuangan (12 Bulan Terakhir)
            </div>
            <div class="card-body">
                <canvas id="lineChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart me-2"></i>Pemasukan per Sumber
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 350px;">
                <canvas id="pieChartPemasukan"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2"></i>Pengeluaran per Kategori
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 350px;">
                <canvas id="pieChartPengeluaran"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>Ringkasan Pemasukan vs Pengeluaran
            </div>
            <div class="card-body">
                <canvas id="barChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-journal-text me-2"></i>Daftar Transaksi
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 1%;">#</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Sumber / Kategori</th>
                        <th>Donatur</th>
                        <th>Metode</th>
                        <th class="text-end">Nominal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keuangan as $row)
                        <tr>
                            <td>{{ $keuangan->firstItem() + $loop->index }}</td>
                            <td>{{ $row->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if($row->tipe === 'pemasukan')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $row->sumber ?? '-' }}</strong>
                                @if($row->deskripsi)
                                    <div class="small text-muted">{{ Str::limit($row->deskripsi, 60) }}</div>
                                @endif
                            </td>
                            <td>{{ $row->donatur ?? '-' }}</td>
                            <td>{{ $row->metode ?? '-' }}</td>
                            <td class="text-end">
                                @if($row->tipe === 'pemasukan')
                                    <span class="text-success">+ Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-danger">- Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.keuangan.edit', $row) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(Auth::guard('admin')->user()?->isSuperAdmin())
                                        <form action="{{ route('admin.keuangan.destroy', $row) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-delete-confirm" data-message="Yakin ingin menghapus transaksi ini?">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada transaksi keuangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $keuangan->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);

    // Line Chart - Trend 12 Bulan
    const lineCtx = document.getElementById('lineChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: chartData.months,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: chartData.pemasukan,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.pengeluaran,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Pie Chart - Pemasukan per Sumber
    const piePemasukanCtx = document.getElementById('pieChartPemasukan');
    if (piePemasukanCtx && chartData.pemasukanBySource.length > 0) {
        const colors = ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#EC4899'];
        new Chart(piePemasukanCtx, {
            type: 'pie',
            data: {
                labels: chartData.pemasukanBySource.map(item => item.label),
                datasets: [{
                    data: chartData.pemasukanBySource.map(item => item.value),
                    backgroundColor: colors.slice(0, chartData.pemasukanBySource.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Pie Chart - Pengeluaran per Kategori
    const piePengeluaranCtx = document.getElementById('pieChartPengeluaran');
    if (piePengeluaranCtx && chartData.pengeluaranBySource.length > 0) {
        const colors = ['#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E'];
        new Chart(piePengeluaranCtx, {
            type: 'pie',
            data: {
                labels: chartData.pengeluaranBySource.map(item => item.label),
                datasets: [{
                    data: chartData.pengeluaranBySource.map(item => item.value),
                    backgroundColor: colors.slice(0, chartData.pengeluaranBySource.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Bar Chart - Pemasukan vs Pengeluaran
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        const totalPemasukan = {{ $totalPemasukan }};
        const totalPengeluaran = {{ $totalPengeluaran }};
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Total'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [totalPemasukan],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran',
                        data: [totalPengeluaran],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 3,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

