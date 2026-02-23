<x-app-layout>
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Dashboard</strong> Admin OPD</h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <a href="{{ route('opd.usulan.create') }}" class="btn btn-primary shadow-sm">
                    <i class="align-middle fa fa-plus me-1"></i> Buat Usulan Baru
                </a>
            </div>
        </div>

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
                            <span class="text-muted">Total usulan instansi Anda</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Sedang Diproses</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-warning bg-warning-light">
                                    <i class="align-middle fa fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-warning">{{ $stats['diproses'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Dalam proses verifikasi</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Ditolak / Revisi</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-danger bg-danger-light">
                                    <i class="align-middle fa fa-exclamation-circle"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-danger">{{ $stats['ditolak'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Perlu perhatian segera</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Selesai / SK Terbit</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat text-success bg-success-light">
                                    <i class="align-middle fa fa-check-double"></i>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 fw-bold text-success">{{ $stats['selesai'] }}</h1>
                        <div class="mb-0 small">
                            <span class="text-muted">Mutasi berhasil tuntas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 d-flex">
                <div class="card flex-fill shadow-sm">
                    <div class="card-header border-0 pb-0">
                        <h5 class="card-title mb-0 fw-bold">Usulan Terbaru Instansi</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover my-0">
                            <thead>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <th>Jumlah PNS</th>
                                    <th class="d-none d-xl-table-cell">Tanggal Usul</th>
                                    <th>Status Terakhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latest_usulans as $lus)
                                <tr>
                                    <td>
                                        <strong>{{ $lus->no_surat }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($lus->perihal, 50) }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $lus->details->count() }} Orang</span></td>
                                    <td class="d-none d-xl-table-cell">{{ $lus->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($lus->status == 5)
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($lus->status == 98)
                                            <span class="badge bg-danger">Ditolak Permanen</span>
                                        @elseif($lus->status == 99)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @elseif($lus->status == 4)
                                            <span class="badge bg-info">Menunggu SK</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Dalam Proses</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('opd.tracking.detail', $lus->id_usulan) }}" class="btn btn-sm btn-outline-primary">Lacak <i class="fa fa-search ms-1"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada usulan mutasi yang diajukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('opd.riwayat') }}" class="btn btn-sm btn-link">Lihat Seluruh Riwayat <i class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pt-4 pb-3">
                        <h5 class="card-title fw-bold text-muted mb-0"><i class="fa fa-list-check text-primary me-2"></i> Persyaratan Dokumen Usulan Kolektif OPD</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 bg-info-light mb-4 text-dark">
                            <i class="fa fa-info-circle me-1"></i> Berikut adalah daftar dokumen mandatori (wajib) yang perlu disiapkan dalam format PDF untuk masing-masing berkas PNS sewaktu Anda membuat usulan.
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
                                    @forelse($dokumenSyarat as $index => $dokumen)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="fw-medium">{{ $dokumen->nama_dokumen }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Belum ada persyaratan dokumen yang diatur admin.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light { background-color: rgba(59, 125, 221, 0.1); }
        .bg-warning-light { background-color: rgba(252, 185, 44, 0.1); }
        .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
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
</x-app-layout>
