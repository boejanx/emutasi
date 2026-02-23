<x-app-layout>
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Dashboard</strong> {{ Auth::user()->role_label }}</h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <span class="btn btn-light bg-white shadow-sm border">
                    <i class="align-middle fa fa-calendar text-primary me-2"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>

        {{-- ================================================= --}}
        {{-- SECTION: STATISTIK (MANAJEMEN: ADMIN/KABID/PIMPINAN) --}}
        {{-- ================================================= --}}
        @if(in_array(Auth::user()->role, [0, 1, 4]))
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Total Usulan</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-primary bg-primary-light">
                                    <i class="align-middle fa fa-file-invoice"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold">{{ $stats['total'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Keseluruhan data masuk</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Butuh Persetujuan</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-warning bg-warning-light">
                                    <i class="align-middle fa fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-warning">{{ $stats['menunggu_pimpinan'] + $stats['menunggu_kabid'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Masih di Pimpinan/Kabid</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Menunggu SK</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-info bg-info-light">
                                    <i class="align-middle fa fa-file-signature"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-info">{{ $stats['menunggu_sk'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Siap diterbitkan SK</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Berhasil Selesai</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-success bg-success-light">
                                    <i class="align-middle fa fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-success">{{ $stats['selesai'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">SK Mutasi sudah terbit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12 col-lg-8 col-xxl-9 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Usulan Terbaru</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover my-0">
                            <thead>
                                <tr>
                                    <th>Nama Pengaju</th>
                                    <th class="d-none d-xl-table-cell">Tanggal Usul</th>
                                    <th>Status</th>
                                    <th class="d-none d-md-table-cell">Posisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_usulans as $lus)
                                <tr>
                                    <td>
                                        <strong>{{ $lus->user->name ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $lus->no_surat }}</small>
                                    </td>
                                    <td class="d-none d-xl-table-cell">{{ $lus->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($lus->status == 5)
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($lus->status == 4)
                                            <span class="badge bg-primary">Menunggu SK</span>
                                        @elseif($lus->status == 99)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Dalam Proses</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($lus->disposisi == 0) Kadin BKPSDM
                                        @elseif($lus->disposisi == 1) Kabid Mutasi
                                        @elseif($lus->disposisi == 2) Staf Teknis
                                        @else - @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        @if(Auth::user()->role == 1)
                            <a href="{{ route('admin.index') }}" class="btn btn-sm btn-link">Lihat Semua Inbox <i class="fa fa-arrow-right"></i></a>
                        @elseif(Auth::user()->role == 0)
                            <a href="{{ route('pimpinan.inbox') }}" class="btn btn-sm btn-link">Lihat Semua Inbox <i class="fa fa-arrow-right"></i></a>
                        @elseif(Auth::user()->role == 4)
                            <a href="{{ route('kabid.inbox') }}" class="btn btn-sm btn-link">Lihat Semua Inbox <i class="fa fa-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 col-xxl-3 d-flex text-center">
                <div class="card flex-fill shadow-sm bg-primary text-white">
                    <div class="card-body py-4">
                        <div class="stat text-white bg-white-50 mb-3 mx-auto" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2);">
                            <i class="fa fa-user-shield fa-2x"></i>
                        </div>
                        <h4 class="text-white mb-1">{{ Auth::user()->name }}</h4>
                        <div class="text-white-50 small mb-4">{{ Auth::user()->role_label }}</div>
                        <div class="mb-3">
                            <p class="px-3">Selamat datang di Sistem Informasi Mutasi Pegawai BKPSDM Kabupaten Pekalongan.</p>
                        </div>
                        <hr class="bg-white-50">
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="text-white-50 small">Selesai</div>
                                <div class="h3 text-white mb-0">{{ $stats['selesai'] }}</div>
                            </div>
                            <div class="col-6 border-start">
                                <div class="text-white-50 small">Ditolak</div>
                                <div class="h3 text-white mb-0">{{ $stats['ditolak'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom pt-3 pb-2">
                        <h5 class="card-title fw-bold text-muted mb-0"><i class="fa fa-chart-line text-primary me-2"></i> Grafik Tren Pengajuan Mutasi Tahun {{ date('Y') }}</h5>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px; width: 100%;">
                            <canvas id="mutasiBulananChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================= --}}
        {{-- SECTION: PNS DASHBOARD (USER BIASA) --}}
        {{-- ================================================= --}}
        @elseif(Auth::user()->role == 3)
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="stat bg-primary-light text-primary rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fa fa-user fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1 fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
                                <p class="text-muted mb-0">NIP: {{ Auth::user()->email }} | Anda memiliki <strong>{{ $total_pengajuan }}</strong> total riwayat pengajuan.</p>
                            </div>
                        </div>

                        @if($my_usulan)
                            <div class="alert {{ $my_usulan->status == 99 ? 'alert-danger' : ($my_usulan->status == 5 ? 'alert-success' : 'alert-info') }} border-0 shadow-sm p-4 d-block w-100">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center mb-3 mb-md-0">
                                        <div class="stat {{ $my_usulan->status == 99 ? 'bg-danger text-white' : ($my_usulan->status == 5 ? 'bg-success text-white' : 'bg-primary text-white') }} me-3 flex-shrink-0 shadow-sm" style="width: 50px; height: 50px;">
                                            <i class="fa {{ $my_usulan->status == 99 ? 'fa-exclamation-triangle' : ($my_usulan->status == 5 ? 'fa-check-double' : 'fa-spinner fa-spin') }} fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1 fw-bold text-dark">Status Usulan Terakhir</h5>
                                            <small class="text-dark opacity-75 fw-medium"><i class="fa fa-file-alt me-1"></i> {{ $my_usulan->no_surat }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-white shadow-sm border {{ $my_usulan->status == 99 ? 'text-danger border-danger' : ($my_usulan->status == 5 ? 'text-success border-success' : 'text-primary border-primary') }} px-3 py-2" style="font-size: 0.85rem;">
                                            {{ $my_usulan->status == 5 ? 'Selesai / SK Terbit' : ($my_usulan->status == 4 ? 'Menunggu SK' : ($my_usulan->status == 99 ? 'Perlu Revisi' : 'Sedang Diproses')) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="progress mb-2 rounded-pill shadow-sm" style="height: 12px; background-color: rgba(0,0,0,0.05);">
                                    @php
                                        $progress = 0;
                                        if($my_usulan->status == 5) $progress = 100;
                                        elseif($my_usulan->status == 4) $progress = 80;
                                        elseif($my_usulan->status == 1) $progress = 20;
                                        elseif($my_usulan->status == 3) $progress = 50;
                                        elseif($my_usulan->status == 99) $progress = 40;
                                    @endphp
                                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $my_usulan->status == 99 ? 'bg-danger' : 'bg-success' }}" style="width:{{ $progress }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between small fw-bold px-1" style="color: rgba(0,0,0,0.5);">
                                    <span>Diajukan</span>
                                    <span>Verifikasi Staf</span>
                                    <span>SK Terbit</span>
                                </div>
                                <div class="mt-4 pt-3 text-end" style="border-top: 1px dashed rgba(0,0,0,0.1);">
                                    <a href="{{ route('pns.tracking.detail', $my_usulan->id_usulan) }}" class="btn {{ $my_usulan->status == 99 ? 'btn-danger' : 'btn-primary' }} fw-bold px-4 shadow-sm">Lihat Detail Tracking <i class="fa fa-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        @else
                            <div class="bg-light p-5 text-center rounded">
                                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Pengajuan Mutasi</h5>
                                <p class="text-muted">Silakan klik tombol di bawah untuk memulai pengajuan mutasi baru.</p>
                                <a href="{{ route('pns.usulan.create') }}" class="btn btn-primary mt-2">Buat Pengajuan Baru</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('pns.usulan.create') }}" class="list-group-item list-group-item-action py-3 border-0 bg-light rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="stat bg-primary text-white me-3">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Buat Pengajuan</div>
                                        <small class="text-muted">Mulai proses usulan mutasi baru</small>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('pns.tracking') }}" class="list-group-item list-group-item-action py-3 border-0 bg-light rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="stat bg-info text-white me-3">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Lacak Usulan</div>
                                        <small class="text-muted">Cek progres berkas secara real-time</small>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action py-3 border-0 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <div class="stat bg-secondary text-white me-3">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Panduan</div>
                                        <small class="text-muted">Cara mengajukan mutasi & syaratnya</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-4 pb-3">
                        <h5 class="card-title fw-bold text-muted mb-0"><i class="fa fa-list-check text-primary me-2"></i> Persyaratan Pengajuan Mutasi Baru</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 bg-info-light mb-4">
                            <i class="fa fa-info-circle me-1"></i> Mohon siapkan dokumen-dokumen persyaratan di bawah ini dalam format PDF sebelum Anda menekan tombol Buat Pengajuan.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">No.</th>
                                        <th>Nama Dokumen Persyaratan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokumenSyarat as $index => $dokumen)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $dokumen->nama_dokumen }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5 text-center">
                        <div class="stat bg-primary-light text-primary rounded-circle mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fa fa-university fa-2x"></i>
                        </div>
                        <h2 class="fw-bold">Selamat Datang, {{ Auth::user()->name }}</h2>
                        <p class="text-muted">Aplikasi e-Mutasi BKPSDM Kabupaten Pekalongan</p>
                        <div class="mt-4">
                            <p>Anda masuk sebagai <strong>{{ Auth::user()->role_label }}</strong>. Silakan gunakan menu di samping untuk melihat data usulan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .stat-icon {
            font-size: 1.5rem;
        }
        .bg-primary-light { background-color: rgba(59, 125, 221, 0.1); }
        .bg-warning-light { background-color: rgba(252, 185, 44, 0.1); }
        .bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
        .stat {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
        }
    </style>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(in_array(Auth::user()->role, [0, 1, 4]) && isset($chart_labels) && isset($monthly_data))
                var ctx = document.getElementById('mutasiBulananChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chart_labels) !!},
                            datasets: [{
                                label: 'Jumlah Pengajuan Mutasi Baru',
                                data: {!! json_encode($monthly_data) !!},
                                backgroundColor: 'rgba(59, 125, 221, 0.7)',
                                borderColor: 'rgba(59, 125, 221, 1)',
                                borderWidth: 1,
                                borderRadius: 4,
                                hoverBackgroundColor: 'rgba(59, 125, 221, 0.9)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
        });
    </script>
</x-app-layout>
