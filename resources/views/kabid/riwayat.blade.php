<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">RIWAYAT DISPOSISI MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Daftar Riwayat Terusan (Bidang Mutasi)</h5>
                        <h6 class="card-subtitle text-muted mt-2">Daftar berkas usulan yang sudah selesai diteruskan/didisposisikan ke staf teknis.</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Pihak Pengaju</th>
                                        <th>No. Surat / Perihal</th>
                                        <th>Tgl. Diajukan</th>
                                        <th>Posisi (Disposisi)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($usulans as $usulan)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $usulan->user->name ?? 'PNS' }}</strong><br>
                                                    <small class="text-muted">{{ $usulan->details->first()->jabatan ?? 'Pegawai' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $usulan->no_surat }}</div>
                                            <small class="text-muted">{{ $usulan->perihal }}</small>
                                        </td>
                                        <td>{{ $usulan->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($usulan->disposisi == 0)
                                                <span class="badge bg-warning text-dark"><i class="fa fa-user-tie"></i> Menunggu Kepala BKPSDM</span>
                                            @elseif($usulan->disposisi == 1)
                                                <span class="badge bg-info text-dark"><i class="fa fa-user-tie"></i> Menunggu Kepala Bidang</span>
                                            @elseif($usulan->disposisi == 2)
                                                <span class="badge bg-success"><i class="fa fa-users-cog"></i> Staf Teknis (Verifikasi)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm text-white btn-disposisi" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalDisposisi-{{ $usulan->id_usulan }}">
                                                <i class="fa fa-eye"></i> Lihat Riwayat
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada surat usulan masuk.</td>
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

    <!-- Kumpulan Modal (Diletakkan di luar tabel agar tidak mengganggu tinggi Baris/Row HTML) -->
    @foreach($usulans as $usulan)
    <div class="modal fade" id="modalDisposisi-{{ $usulan->id_usulan }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"><i class="align-middle me-2" data-feather="history"></i> Rekam Jejak Terusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="alert alert-primary p-2 mb-4">
                        <i class="align-middle me-2" data-feather="file-text"></i> <strong>Surat Nomor:</strong> {{ $usulan->no_surat }}
                    </div>

                    @if($usulan->status == 5 && $usulan->path_sk)
                    <div class="card border-success mb-4">
                        <div class="card-body bg-light py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-success fw-bold"><i class="fa fa-file-signature me-1"></i> SK MUTASI TERBIT</h6>
                                    <small class="text-muted">Nomor SK: {{ $usulan->nomor_sk }}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-success view-pdf-btn" data-url="{{ asset('storage/' . $usulan->path_sk) }}" data-target="#pdf-container-sk-{{ $usulan->id_usulan }}">
                                    <i class="fa fa-eye"></i> Lihat SK
                                </button>
                            </div>
                            <div id="pdf-container-sk-{{ $usulan->id_usulan }}" class="collapse mt-3">
                                <div class="ratio ratio-16x9" style="max-height: 500px;">
                                    <iframe src="" width="100%" height="500px" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="h6 mb-3 border-bottom pb-2">Data Pegawai & Berkas Lampiran</h5>
                            @foreach($usulan->details as $detail)
                                <table class="table table-sm table-borderless mb-2">
                                    <tr><td width="150" class="text-muted small">Nama / NIP</td><td class="small fw-bold">: {{ $detail->nama }} / {{ $detail->nip }}</td></tr>
                                    <tr><td class="text-muted small">Jabatan</td><td class="small">: {{ $detail->jabatan }}</td></tr>
                                </table>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle mb-4">
                                        <thead class="table-light small">
                                            <tr>
                                                <th>Nama Dokumen</th>
                                                <th class="text-center" style="width: 100px;">File</th>
                                                <th class="text-center" style="width: 120px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @foreach($detail->berkas as $berkas)
                                            <tr>
                                                <td>{{ $berkas->dokumen->nama_dokumen ?? 'Dokumen' }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-xs btn-outline-info p-1 view-pdf-btn" data-url="{{ asset('storage/' . $berkas->path_dokumen) }}" data-target="#pdf-container-{{ $berkas->id_berkas }}">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    @if($berkas->status == 1)
                                                        <span class="badge bg-success">Valid</span>
                                                    @elseif($berkas->status == 2)
                                                        <span class="badge bg-danger">Tidak Valid</span>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr id="pdf-container-{{ $berkas->id_berkas }}" class="collapse bg-light">
                                                <td colspan="3" class="p-3">
                                                    <div class="ratio ratio-16x9" style="max-height: 500px;">
                                                        <iframe src="" width="100%" height="500px" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <h6 class="h6 mb-3"><i class="align-middle me-1" data-feather="clock"></i> Riwayat Aktivitas & Log Sistem</h6>
                    <ul class="mb-0" style="list-style-type: none; padding-left: 0;">
                        @forelse($usulan->logs as $log)
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if(str_contains(strtolower($log->aksi), 'disposisi') || str_contains(strtolower($log->aksi), 'teruskan'))
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-share"></i></div>
                                        @elseif($log->aksi == 'UPLOAD_SK')
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-file-signature"></i></div>
                                        @else
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-check"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 border rounded p-2 bg-light shadow-sm">
                                        <strong>{{ $log->aksi }}</strong> &bull; <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small><br />
                                        <small class="text-secondary"><i class="fa fa-user" style="width: 12px; height: 12px;"></i> {{ $log->user->name ?? 'Sistem' }}</small>
                                        @if($log->status_usulan)
                                            <span class="badge bg-secondary ms-1">{{ $log->status_usulan }}</span>
                                        @endif
                                        @if($log->keterangan)
                                            <div class="mt-1 small fst-italic text-muted">"{{ $log->keterangan }}"</div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted small">Belum ada riwayat aktivitas dicatat.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <style>
        .custom-radio-box {
            transition: all 0.2s ease-in-out;
        }
        .custom-radio-box:hover {
            border-color: #3b7ddd !important;
            background-color: #f8fbff !important;
        }
        .custom-radio-box input[type="radio"]:checked + label span.text-dark {
            color: #3b7ddd !important;
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert Session Notification
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                });
            @endif

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
                        if (this.innerHTML.includes('SK')) {
                            this.innerHTML = '<i class="fa fa-eye"></i> Lihat SK';
                            this.classList.replace('btn-secondary', 'btn-success');
                        } else {
                            this.innerHTML = '<i class="fa fa-eye"></i> Lihat';
                            this.classList.replace('btn-secondary', 'btn-outline-info');
                        }
                    } else {
                        iframe.src = url;
                        targetRow.classList.add('show');
                        if (this.innerHTML.includes('SK')) {
                            this.innerHTML = '<i class="fa fa-eye-slash"></i> Tutup SK';
                            this.classList.replace('btn-success', 'btn-secondary');
                        } else {
                            this.innerHTML = '<i class="fa fa-eye-slash"></i> Tutup';
                            this.classList.replace('btn-outline-info', 'btn-secondary');
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
