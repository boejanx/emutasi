<x-app-layout>
    <div class="container-fluid p-0">
        <div class="mb-3">
            <a href="{{ route('opd.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>

        <h1 class="h3 mb-3">PELACAKAN USULAN KOLEKTIF</h1>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Detail Pengajuan: {{ $usulan->no_surat }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row mb-4 bg-light p-3 rounded mx-1">
                            <div class="col-md-7">
                                <p class="mb-1 small text-muted">Perihal:</p>
                                <p class="fw-bold mb-3">{{ $usulan->perihal }}</p>
                                
                                <div class="row small">
                                    <div class="col-4 text-muted">Tanggal Usul</div>
                                    <div class="col-8">: {{ $usulan->created_at->format('d F Y') }}</div>
                                    <div class="col-4 text-muted">Status Terakhir</div>
                                    <div class="col-8">: 
                                        <span class="badge {{ $usulan->status == 5 ? 'bg-success' : ($usulan->status == 99 ? 'bg-danger' : 'bg-primary') }}">
                                            {{ $usulan->status == 5 ? 'Selesai' : ($usulan->status == 4 ? 'Menunggu SK' : 'Diproses') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 border-start text-center d-flex align-items-center justify-content-center">
                                <div>
                                    <p class="mb-0 text-muted small">Jumlah PNS</p>
                                    <h2 class="fw-bold mb-0">{{ $usulan->details->count() }}</h2>
                                    <p class="mb-0 small text-primary">Orang</p>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3"><i class="fa fa-users me-2 text-primary"></i>Daftar PNS & Status Berkas</h6>
                        <div class="accordion" id="accordionTracking">
                            @foreach($usulan->details as $idx => $detail)
                            <div class="accordion-item border shadow-none mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#pns-{{ $detail->id_detail }}">
                                        <div class="d-flex justify-content-between w-100 me-3">
                                            <span><strong>{{ $detail->nama }}</strong> <small class="text-muted">({{ $detail->nip }})</small></span>
                                            @if($detail->status == 1) <span class="badge bg-success-light text-success small">Valid</span>
                                            @elseif($detail->status == 2) <span class="badge bg-danger-light text-danger small">Revisi</span>
                                            @else <span class="badge bg-light text-dark small">Pending</span> @endif
                                        </div>
                                    </button>
                                </h2>
                                <div id="pns-{{ $detail->id_detail }}" class="accordion-collapse collapse" data-bs-parent="#accordionTracking">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            @foreach($detail->berkas as $berkas)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center p-2 border rounded bg-white">
                                                    <div class="flex-shrink-0">
                                                        <i class="fa fa-file-pdf text-danger fa-2x"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3 overflow-hidden">
                                                        <p class="mb-0 small fw-bold text-truncate">{{ $berkas->dokumen->nama_dokumen }}</p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            @if($berkas->status == 1) <span class="text-success small"><i class="fa fa-check-circle"></i> Sesuai</span>
                                                            @elseif($berkas->status == 2) <span class="text-danger small"><i class="fa fa-times-circle"></i> Ditolak</span>
                                                            @else <span class="text-muted small">Belum Dicek</span> @endif
                                                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none view-pdf-btn" data-url="{{ asset('storage/' . $berkas->path_dokumen) }}" data-target="#pdf-container-{{ $berkas->id_berkas }}">Lihat</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="pdf-container-{{ $berkas->id_berkas }}" class="collapse mt-2 px-1">
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Riwayat Log Tracking</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="timeline-simple">
                            @foreach($usulan->logs as $log)
                            <div class="timeline-item d-flex mb-4">
                                <div class="timeline-icon me-3">
                                    <div class="bg-primary rounded-circle shadow-sm" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                                </div>
                                <div class="timeline-content mt-n1 w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0 fw-bold">{{ $log->status_usulan }}</h6>
                                        <small class="text-muted">{{ $log->created_at->format('d/m H:i') }}</small>
                                    </div>
                                    <p class="text-muted small mb-1">{{ $log->keterangan }}</p>
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="bg-light px-2 py-1 rounded small text-muted">
                                            <i class="fa fa-user me-1" style="font-size: 0.7rem;"></i> {{ $log->user->name ?? 'Sistem' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @if($usulan->status == 5 && $usulan->path_sk)
                        <div class="mt-4 p-3 bg-success rounded text-white shadow-sm">
                            <h6 class="text-white fw-bold mb-2"><i class="fa fa-certificate me-2"></i>SK MUTASI TERBIT</h6>
                            <p class="small mb-3 opacity-75">Proses mutasi telah selesai sepenuhnya. Silakan unduh SK Mutasi di bawah ini.</p>
                            <button type="button" class="btn btn-sm btn-light text-success fw-bold w-100 view-pdf-btn" data-url="{{ asset('storage/' . $usulan->path_sk) }}" data-target="#pdf-container-sk">
                                <i class="fa fa-eye me-1"></i> LIHAT SK RESMI
                            </button>
                            <div id="pdf-container-sk" class="collapse mt-3">
                                <div class="ratio ratio-4x3 bg-light rounded">
                                    <iframe src="" width="100%" height="400px" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                </div>
            </div>

            <!-- Start Panel Helpdesk / Diskusi Resolusi Berkas -->
            <div class="col-12 mt-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-bottom bg-white d-flex align-items-center">
                        <i class="fa fa-comments text-primary fa-lg me-2"></i>
                        <h5 class="card-title mb-0 fw-bold">Catatan Revisi & Helpdesk</h5>
                    </div>
                    <div class="card-body bg-light-50">
                        <div class="chat-container mb-3" style="max-height: 400px; overflow-y: auto;">
                            @forelse($usulan->pesans ?? [] as $pesan)
                                @if($pesan->id_user == auth()->id())
                                    <div class="d-flex justify-content-end mb-3">
                                        <div class="bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 75%;">
                                            <p class="mb-1 small">{{ $pesan->pesan }}</p>
                                            <small class="text-white-50 float-end" style="font-size: 0.70rem;">{{ $pesan->created_at->format('d/m H:i') }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-start mb-3">
                                        <div class="me-2">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 35px; height: 35px;">
                                                <i class="fa fa-user-shield"></i>
                                            </div>
                                        </div>
                                        <div class="bg-white border p-3 rounded-3 shadow-sm" style="max-width: 75%;">
                                            <div class="fw-bold small text-primary mb-1">{{ preg_replace('/\s+/', ' ', ucwords(strtolower($pesan->user->name ?? 'Admin'))) }}</div>
                                            <p class="mb-1 small text-dark">{{ $pesan->pesan }}</p>
                                            <small class="text-muted float-end" style="font-size: 0.70rem;">{{ $pesan->created_at->format('d/m H:i') }}</small>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center text-muted my-5">
                                    <i class="fa fa-envelope-open-text fa-3x mb-3 text-secondary opacity-50"></i>
                                    <h6 class="fw-bold">Belum Ada Percakapan</h6>
                                    <p class="small mb-0">Gunakan fitur ini untuk bertanya atau memberikan keterangan revisi kepada Admin verifikator.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Form Input Pesan -->
                        <form action="{{ route('usulan.pesan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_usulan" value="{{ $usulan->id_usulan }}">
                            <div class="input-group">
                                <input type="text" name="pesan" class="form-control bg-white" placeholder="Ketik pesan, pertanyaan, atau tanggapan terkait berkas revisi di sini..." required>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold"><i class="fa fa-paper-plane me-1"></i> Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Diskusi -->
        </div>
    </div>

    <style>
        .timeline-simple { position: relative; padding-left: 5px; }
        .timeline-simple:before { content: ''; position: absolute; left: 10px; top: 10px; bottom: 0; width: 2px; background: #e9ecef; }
        .timeline-item { position: relative; z-index: 1; }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
        .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
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
                        if (this.innerHTML.includes('SK')) {
                            this.innerHTML = '<i class="fa fa-eye me-1"></i> LIHAT SK RESMI';
                        } else {
                            this.innerHTML = 'Lihat';
                        }
                    } else {
                        iframe.src = url;
                        targetRow.classList.add('show');
                        if (this.innerHTML.includes('SK')) {
                            this.innerHTML = '<i class="fa fa-eye-slash me-1"></i> TUTUP SK RESMI';
                        } else {
                            this.innerHTML = 'Tutup';
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
