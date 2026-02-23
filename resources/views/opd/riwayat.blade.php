<x-app-layout>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">RIWAYAT USULAN INSTANSI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover my-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nomor Surat</th>
                                        <th>Perihal</th>
                                        <th>Jml PNS</th>
                                        <th>Status Terakhir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usulans as $usulan)
                                    <tr>
                                        <td>{{ $usulan->created_at->format('d/m/Y') }}<br><small class="text-muted">{{ $usulan->created_at->format('H:i') }}</small></td>
                                        <td><strong>{{ $usulan->no_surat }}</strong></td>
                                        <td>{{ Str::limit($usulan->perihal, 60) }}</td>
                                        <td><span class="badge bg-secondary">{{ $usulan->details->count() }} Orang</span></td>
                                        <td>
                                            @if($usulan->status == 5)
                                                <span class="badge bg-success">Selesai / SK Terbit</span>
                                            @elseif($usulan->status == 98)
                                                <span class="badge bg-danger">Ditolak Permanen</span>
                                            @elseif($usulan->status == 99)
                                                <span class="badge bg-danger">Ditolak / Perlu Revisi</span>
                                            @elseif($usulan->status == 4)
                                                <span class="badge bg-info">Menunggu Upload SK</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Proses Verifikasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $usulan->id_usulan }}">
                                                <i class="fa fa-eye me-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>


                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fa fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                                            Belum ada data riwayat usulan untuk instansi Anda.
                                        </td>
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

    @foreach($usulans as $usulan)
    <!-- Modal Detail for OPD -->
    <div class="modal fade" id="modalDetail-{{ $usulan->id_usulan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Detail Usulan: {{ $usulan->no_surat }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr><td width="30%" class="text-muted">Tanggal Usul</td><td>: {{ $usulan->created_at->format('d F Y H:i') }}</td></tr>
                                <tr><td class="text-muted">No. Surat</td><td>: {{ $usulan->no_surat }}</td></tr>
                                <tr><td class="text-muted">Perihal</td><td>: {{ $usulan->perihal }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info py-2">
                                <div class="fw-bold d-block mb-1">Status Saat Ini:</div>
                                <span class="fw-semibold">
                                    @if($usulan->status == 5) Selesai - SK Telah Terbit
                                    @elseif($usulan->status == 4) Menunggu Verifikator Mengunggah SK
                                    @elseif($usulan->status == 98) Berkas Ditolak Permanen
                                    @elseif($usulan->status == 99) Berkas Ditolak / Perlu Diperbaiki
                                    @else Sedang Diverifikasi di BKPSDM @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="fa fa-users me-2"></i>Daftar PNS dalam Usulan Ini</h6>
                    <div class="accordion mb-4" id="accordionPns-{{ $usulan->id_usulan }}">
                        @foreach($usulan->details as $idx => $detail)
                        <div class="accordion-item shadow-none border mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#pnsDetail-{{ $detail->id_detail }}">
                                    <div class="d-flex justify-content-between w-100 me-3">
                                        <span><strong>{{ $detail->nama }}</strong> ({{ $detail->nip }})</span>
                                        <span class="small text-muted">{{ $detail->jabatan }}</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="pnsDetail-{{ $detail->id_detail }}" class="accordion-collapse collapse" data-bs-parent="#accordionPns-{{ $usulan->id_usulan }}">
                                <div class="accordion-body bg-light-50">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <table class="table table-sm table-borderless small">
                                                <tr><td width="40%">NIP</td><td>: {{ $detail->nip }}</td></tr>
                                                <tr><td>Nama</td><td>: {{ $detail->nama }}</td></tr>
                                                <tr><td>Jabatan</td><td>: {{ $detail->jabatan }}</td></tr>
                                                <tr><td>Asal</td><td>: {{ $detail->lokasi_awal }}</td></tr>
                                                <tr><td>Tujuan</td><td>: {{ $detail->lokasi_tujuan }}</td></tr>
                                            </table>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="card shadow-none border card-body py-2 px-3 small">
                                                <p class="fw-bold mb-2">Lampiran Berkas:</p>
                                                <div class="row g-2">
                                                    @foreach($detail->berkas as $berkas)
                                                    <div class="col-12 mb-1 border-bottom pb-2">
                                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none d-flex align-items-center view-pdf-btn" data-url="{{ asset('storage/' . $berkas->path_dokumen) }}" data-target="#pdf-opd-{{ $berkas->id_berkas }}">
                                                            <i class="fa fa-file-pdf text-danger me-2"></i>
                                                            <span class="text-truncate fw-bold">{{ $berkas->dokumen->nama_dokumen }}</span>
                                                        </button>
                                                        <div id="pdf-opd-{{ $berkas->id_berkas }}" class="collapse mt-2">
                                                            <div class="ratio ratio-16x9">
                                                                <iframe src="" width="100%" height="250px" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3"><i class="fa fa-history me-2"></i>Log Aktivitas Tracking</h6>
                    <div class="timeline small mt-3 px-3">
                        @foreach($usulan->logs as $log)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 text-center" style="width: 40px;">
                                <i class="fa {{ $log->aksi == 'UPLOAD_SK' ? 'fa-certificate text-success' : ($log->aksi == 'VERIFIKASI_DITOLAK' ? 'fa-times-circle text-danger' : 'fa-circle-check text-primary') }} mt-1"></i>
                            </div>
                            <div class="flex-grow-1 ms-2 border-start ps-3 pb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">{{ $log->status_usulan }}</span>
                                    <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 text-muted">{{ $log->keterangan }}</p>
                                <small class="text-muted">Oleh: {{ $log->user->name ?? 'Sistem' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($usulan->status == 5 && $usulan->path_sk)
                    <div class="alert alert-success d-flex align-items-center mt-4">
                        <i class="fa fa-certificate fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">SK Mutasi Telah Terbit</h6>
                            <p class="mb-1 small">SK Nomor: {{ $usulan->nomor_sk }}</p>
                            <button type="button" class="btn btn-sm btn-success view-pdf-btn mt-2" data-url="{{ asset('storage/' . $usulan->path_sk) }}" data-target="#pdf-opd-sk-{{ $usulan->id_usulan }}">
                                <i class="fa fa-eye me-1"></i> Lihat SK Mutasi
                            </button>
                            <div id="pdf-opd-sk-{{ $usulan->id_usulan }}" class="collapse mt-3 w-100">
                                <div class="ratio ratio-16x9 bg-light" style="max-height: 400px;">
                                    <iframe src="" width="100%" height="400px" style="border: 1px solid white; border-radius: 4px;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <style>
        .timeline .d-flex:last-child .flex-grow-1 { border-left: none !important; }
        .bg-light-50 { background-color: #fafbfc; }
        .accordion-button:not(.collapsed) { background-color: #f1f6fe; color: #3b7ddd; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic for inline PDF viewer
            document.querySelectorAll('.view-pdf-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    const url = this.getAttribute('data-url');
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.querySelector(targetId);
                    
                    if (!targetRow) return;
                    
                    const iframe = targetRow.querySelector('iframe');
                    
                    if (targetRow.classList.contains('show')) {
                        targetRow.classList.remove('show');
                        setTimeout(() => {
                            iframe.src = '';
                        }, 300); 
                    } else {
                        iframe.src = url;
                        targetRow.classList.add('show');
                    }
                });
            });
        });
    </script>
</x-app-layout>
