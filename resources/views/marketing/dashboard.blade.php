@extends('layouts.admin2')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">📊 Dashboard Marketing</h3>
            <h6 class="op-7 mb-2">Statistik Performa Bulan {{ now()->translatedFormat('F Y') }}</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Lead Masuk</p>
                                <h4 class="card-title">{{ $leadMasuk }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-handshake"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Closing</p>
                                <h4 class="card-title">{{ $closing }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-round shadow-sm">
                <div class="card-body">
                    <div class="card-title fw-bold">Target Bulanan</div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Progres: {{ round($progress) }}%</span>
                        <span class="fw-bold text-primary">{{ $target ? 'Rp ' . number_format($target->target_omset,0,',','.') : 'Belum Diset' }}</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: {{ $progress }}%" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="mt-2 text-muted small">
                        Total Omset Saat Ini: <strong>Rp {{ number_format($totalOmset,0,',','.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">📈 Lead per Marketing</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 300px">
                        <canvas id="kpiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">📅 Follow-Up Hari Ini</div>
                </div>
                <div class="card-body pb-0">
                    <div id="followUpList" style="max-height: 320px; overflow-y: auto;">
                        @forelse($followups as $f)
                            <div class="d-flex align-items-center border-bottom py-3">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-title rounded-circle border border-white bg-warning text-dark">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                                <div class="flex-1 pt-1 ml-2">
                                    <h6 class="fw-bold mb-1">{{ $f->konsumen ? $f->konsumen->nama : '-' }}</h6>
                                    <small class="text-muted">{{ $f->konsumen ? $f->konsumen->no_hp : '-' }}</small>
                                </div>
                                <div class="d-flex ms-auto align-items-center">
                                    <span class="badge badge-warning">{{ $f->status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="text-muted">Tidak ada jadwal hari ini</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer text-center bg-white border-0">
                        <a href="{{ route('followups.index') }}" class="btn btn-primary btn-sm btn-round">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Chart Perbaikan agar tidak kotak ---
    const kpiCtx = document.getElementById('kpiChart').getContext('2d');
    new Chart(kpiCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kpi->map(fn($item) => $item->user ? $item->user->name : 'Unknown')) !!},
            datasets: [{
                label: 'Lead Masuk',
                data: {!! json_encode($kpi->pluck('total')) !!},
                backgroundColor: 'rgba(23, 125, 255, 0.7)',
                borderColor: '#177dff',
                borderWidth: 1,
                borderRadius: 10, // Membuat batang melengkung
                borderSkipped: false,
                barPercentage: 0.5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // --- Live update notification ---
    async function fetchFollowupsToday() {
        try {
            const response = await fetch('{{ route("marketing.followups.today") }}');
            const data = await response.json();

            const badge = document.getElementById('followup-count');
            const textCount = document.getElementById('followup-count-text');
            
            if(badge) badge.textContent = data.length;
            if(textCount) textCount.textContent = data.length;

            // Logic dropdown bisa ditambahkan di sini jika navbar berada dalam satu file
        } catch (err) {
            console.error('Error fetching data:', err);
        }
    }

    setInterval(fetchFollowupsToday, 60000); // Cek setiap 1 menit
</script>
@endpush
@endsection