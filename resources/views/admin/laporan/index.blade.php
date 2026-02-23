<x-app-layout>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3"><strong>Laporan</strong> Rekapitulasi Mutasi</h1>

        <!-- FILTER FORM -->
        <div class="card shadow-sm border-0 mb-4 d-print-none">
            <div class="card-body">
                <form action="{{ route('admin.laporan') }}" method="GET" class="row align-items-end g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select class="form-select" name="tahun">
                            <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @php $currentYear = date('Y'); @endphp
                            @for($i = $currentYear; $i >= $currentYear - 3; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select class="form-select" name="bulan">
                            <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @php
                                $months = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="filter" value="true" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> Terapkan Filter</button>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-outline-success" onclick="window.print()">
                            <i class="fa fa-print me-1"></i> Cetak PDF Laporan
                        </button>
                        <button type="submit" name="export" value="excel" class="btn btn-outline-primary">
                            <i class="fa fa-download me-1"></i> Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- REKAPITULASI STATS -->
        <div class="row d-print-none">
            <div class="col-sm-3">
                <div class="card shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h4 class="text-white mb-2">Total Pengajuan</h4>
                        <h1 class="display-5 text-white fw-bold mb-0">{{ $rekap['total'] }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow-sm" style="background-color: #fca311; color: white;">
                    <div class="card-body text-center">
                        <h4 class="text-white mb-2">Sedang Diproses</h4>
                        <h1 class="display-5 text-white fw-bold mb-0">{{ $rekap['proses'] }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4 class="text-white mb-2">Disetujui / Selesai</h4>
                        <h1 class="display-5 text-white fw-bold mb-0">{{ $rekap['selesai'] }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h4 class="text-white mb-2">Berkas Ditolak</h4>
                        <h1 class="display-5 text-white fw-bold mb-0">{{ $rekap['ditolak'] }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white pb-0">
                <h5 class="card-title fw-bold">Rincian Daftar Usulan Mutasi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mt-2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PNS Yang Diusulkan</th>
                                <th>No Registrasi / Surat</th>
                                <th>Tanggal Masuk</th>
                                <th>Progress Berkas</th>
                                <th>Status Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usulans as $index => $u)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @foreach($u->details as $detail)
                                            <div class="mb-1">
                                                <strong>{{ $detail->nama ?? '-' }}</strong><br>
                                                <small class="text-muted">NIP. {{ $detail->nip ?? '-' }}</small>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $u->no_surat }}</td>
                                    <td>{{ $u->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($u->status == 1) Verifikasi Instansi (OPD)
                                        @elseif($u->status == 2) Menunggu Disposisi Asisten/Sekda
                                        @elseif($u->status == 3) Penelaahan Teknis BKPSDM
                                        @elseif($u->status == 4) Pencetakan & TTE SK
                                        @elseif($u->status == 5) Arsip Akhir
                                        @else Ditolak / Kembali @endif
                                    </td>
                                    <td>
                                        @if($u->status == 5)
                                            <span class="badge bg-success">Selesai SK Terbit</span>
                                        @elseif($u->status == 99)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Dalam Proses</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data pengajuan pada periode (bulan/tahun) ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* Sembunyikan sidebar dan navbar saat dicetak, hanya tampilkan tabel laporan */
        @media print {
            .sidebar, .navbar, .btn, footer { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            .card-header h5 { color: black !important; font-size: 24pt !important; text-align: center; width:100%; display:block;}
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #ddd !important; padding: 8px !important; }
        }
    </style>
</x-app-layout>
