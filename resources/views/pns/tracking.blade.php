<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">TRACKING PENGAJUAN MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Usulan Mutasi</h5>
                        <h6 class="card-subtitle text-muted">Berikut adalah daftar usulan mutasi yang pernah anda ajukan.</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>No. Surat / Tgl</th>
                                    <th>Perihal</th>
                                    <th>Status Usulan</th>
                                    <th>Tgl. Diajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usulans as $usulan)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $usulan->no_surat }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($usulan->tanggal_surat)->format('d M Y') }}</small>
                                    </td>
                                    <td>{{ $usulan->perihal }}</td>
                                    <td>
                                        @if($usulan->status == 98)
                                            <span class="badge bg-danger">Ditolak Permanen</span>
                                        @elseif($usulan->status == 99)
                                            <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle"></i> Perlu Revisi</span>
                                        @elseif($usulan->status >= 4)
                                            <span class="badge bg-success">Usulan Selesai Diproses</span>
                                        @elseif($usulan->disposisi >= 2)
                                            <span class="badge bg-warning">Surat Usulan Diproses</span>
                                        @elseif($usulan->disposisi >= 1)
                                            <span class="badge bg-info">Surat Usulan Diterima</span>
                                        @else
                                            <span class="badge bg-primary">Surat Usulan Dikirim</span>
                                        @endif
                                    </td>
                                    <td>{{ $usulan->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('pns.tracking.detail', $usulan->id_usulan) }}" class="btn btn-sm btn-info border-0 rounded-pill px-3 shadow-sm text-white">
                                            <i class="fa fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada data usulan mutasi yang terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
