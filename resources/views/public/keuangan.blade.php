@extends('layouts.public')

@section('title', 'Laporan Keuangan & Donasi')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-widget">
            <div class="stats-icon">
                <i class="bi bi-arrow-down-circle-fill"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stats-label">Total Pemasukan (Donasi & Lainnya)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-widget">
            <div class="stats-icon">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </div>
            <div class="stats-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stats-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-widget">
            <div class="stats-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stats-value {{ $saldo < 0 ? 'text-danger' : 'text-success' }}">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </div>
            <div class="stats-label">Saldo Akhir</div>
        </div>
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-journal-text me-2"></i>Laporan Keuangan</span>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.public.export', ['format' => 'excel']) }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
            </a>
            <a href="{{ route('keuangan.public.export', ['format' => 'csv']) }}" class="btn btn-info btn-sm">
                <i class="bi bi-filetype-csv me-1"></i>Export CSV
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 1%;">#</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Sumber / Keterangan</th>
                        <th>Donatur</th>
                        <th class="text-end">Nominal</th>
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
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $row->sumber ?? '-' }}</strong>
                                @if($row->deskripsi)
                                    <div class="small text-muted">{{ Str::limit($row->deskripsi, 80) }}</div>
                                @endif
                            </td>
                            <td>{{ $row->donatur ?? '-' }}</td>
                            <td class="text-end">
                                @if($row->tipe === 'pemasukan')
                                    <span class="text-success">+ Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-danger">- Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data keuangan yang dapat ditampilkan.
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

