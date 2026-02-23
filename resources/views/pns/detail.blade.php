<x-app-layout>
    <div class="container-fluid p-0">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 d-inline align-middle">DETAIL PENGAJUAN MUTASI</h1>
            @if(in_array(auth()->user()->role, [0, 1, 4]))
                <a href="{{ route('admin.tracking') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
            @else
                <a href="{{ route('pns.tracking') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
            @endif
        </div>

        @if(auth()->user()->role == 1 && $data->status == 5 && $data->siasnUnorJabatan)
            <div class="card mb-4 border-{{ $data->siasnUnorJabatan->is_sync ? 'success' : 'warning' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1 text-{{ $data->siasnUnorJabatan->is_sync ? 'success' : 'warning' }}">
                                <i class="fa fa-server me-2"></i> Integrasi SIASN BKN
                            </h5>
                            @if($data->siasnUnorJabatan->is_sync)
                                <p class="mb-0 text-muted small">Data mutasi ini telah berhasil disinkronisasikan secara otomatis ke database pusat BKN.</p>
                                <p class="mb-0 small fw-bold">ID Riwayat BKN: {{ $data->siasnUnorJabatan->id_riwayat_jabatan_siasn ?? '-' }}</p>
                            @else
                                <p class="mb-0 text-muted small">Usulan ini telah selesai. Menunggu sinkronisasi mutasi ke database SIASN BKN.</p>
                                @if($data->siasnUnorJabatan->sync_response && isset($data->siasnUnorJabatan->sync_response['error']))
                                    <p class="mb-0 small text-danger"><i class="fa fa-exclamation-triangle"></i> Gagal Sync: {{ $data->siasnUnorJabatan->sync_response['error'] }}</p>
                                @endif
                            @endif
                        </div>
                        @if(!$data->siasnUnorJabatan->is_sync)
                            <form action="{{ route('admin.siasn.sync', $data->id_usulan) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning shadow-sm fw-bold px-4" onclick="return confirm('Anda yakin akan mengirim data ini ke SIASN BKN? \n\nPastikan data pns dan jabatan tujuan valid.')">
                                    <i class="fa fa-paper-plane me-2"></i> Kirim ke SIASN
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0 text-white"><i class="fa fa-user-circle me-2"></i> Pegawai & Berkas</h5>
                    </div>
                    <div class="card-body">
                        @foreach($data->details as $detail)
                        <div class="mb-4">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="35%" class="text-muted">NIP</th><td class="fw-bold">: {{ $detail->nip }}</td></tr>
                                <tr><th class="text-muted">Nama Lengkap</th><td class="fw-bold">: {{ $detail->nama }}</td></tr>
                                <tr><th class="text-muted">Jabatan</th><td>: {{ $detail->jabatan }}</td></tr>
                                <tr><th class="text-muted">Instansi Awal</th><td>: {{ $detail->lokasi_awal }}</td></tr>
                                <tr><th class="text-muted">Instansi Tujuan</th><td>: {{ $detail->lokasi_tujuan }}</td></tr>
                                <tr>
                                    <th class="text-muted">Status Data PNS</th>
                                    <td>
                                        : 
                                        @if($detail->status == 1 || in_array($data->status, [4, 5]))
                                            <span class="badge bg-success">Valid / Lengkap</span>
                                        @elseif($detail->status == 2)
                                            <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle"></i> Revisi Berkas</span>
                                        @elseif($data->status == 98)
                                            <span class="badge bg-danger">Ditolak Permanen</span>
                                        @elseif($data->status == 99)
                                            <span class="badge bg-warning text-dark">Berkas Perlu Revisi</span>
                                        @else
                                            <span class="badge bg-secondary">Menunggu Verifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <h6 class="fw-bold mb-3 border-bottom pb-2">Status Dokumen Persyaratan:</h6>
                        <ul class="list-group list-group-flush mb-4">
                            @forelse($detail->berkas as $berkas)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <div class="text-truncate" style="max-width: 70%;">
                                    <i class="fa fa-file-pdf text-danger me-2"></i>
                                    {{ ucwords(str_replace('_', ' ', $berkas->dokumen->nama_dokumen ?? 'Dokumen')) }}
                                    @if($berkas->status == 2)
                                        <span class="badge bg-danger ms-2" title="Berkas ini Ditolak/Tidak Valid">Tidak Valid</span>
                                    @elseif($berkas->status == 1)
                                        <span class="badge bg-success ms-2" title="Berkas Sesuai">Sesuai</span>
                                    @endif
                                </div>
                                <div class="text-nowrap ms-2">
                                    <button type="button" class="btn btn-sm btn-outline-info me-1 view-pdf-btn" data-url="{{ asset('storage/' . $berkas->path_dokumen) }}" data-target="#pdf-pns-{{ $berkas->id_berkas }}" title="Lihat PDF">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if(in_array($data->status, [3, 99]) && ($berkas->status == 2)) 
                                        <button class="btn btn-sm btn-warning text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#revisiModal-{{ $berkas->id_berkas }}">
                                            <i class="fa fa-upload"></i> Upload Revisi
                                        </button>
                                    @endif
                                </div>
                            </li>
                            <li id="pdf-pns-{{ $berkas->id_berkas }}" class="list-group-item collapse bg-light p-3">
                                <div class="ratio ratio-16x9">
                                    <iframe src="" width="100%" height="400px" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
                                </div>
                            </li>

                            <!-- Modal Revisi -->
                            @if(in_array($data->status, [3, 99]) && ($berkas->status == 2)) 
                            <div class="modal fade" id="revisiModal-{{ $berkas->id_berkas }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('pns.usulan.revisi', $berkas->id_berkas) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title h4"><i class="align-middle me-2" data-feather="upload-cloud"></i> Upload Revisi Dokumen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body m-3">
                                                <div class="alert alert-warning">
                                                    <strong>{{ $berkas->dokumen->nama_dokumen ?? 'Dokumen' }}</strong> ini ditolak oleh verifikator. Silakan unggah dokumen yang benar dalam format PDF (Maks. 2MB).
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Pilih File Baru (.pdf) <span class="text-danger">*</span></label>
                                                    <input type="file" class="form-control mt-2" name="file_revisi" accept=".pdf" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Revisi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @empty
                            <div class="alert alert-danger"><small>Belum ada berkas terlampir.</small></div>
                            @endforelse
                        </ul>
                        
                        @if($data->status == 99)
                            <div class="alert text-center p-3 mt-4" style="border: dashed 2px #dc3545; background-color: #fffafb;">
                                <h6 class="text-danger fw-bold"><i class="fa fa-info-circle me-1"></i> Perhatian!</h6>
                                <p class="small text-muted mb-0">Usulan Anda ditolak karena terdapat berkas yang tidak lengkap. Silakan klik tombol <strong>"Upload Revisi"</strong> pada berkas yang ditandai <span class="badge bg-danger">Tidak Valid</span> di atas.</p>
                            </div>
                        @endif

                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0"><i class="fa fa-clock-rotate-left me-2"></i> Riwayat Progres Usulan</h5>
                    </div>
                    <div class="card-body">
                        <div class="container p-2">
                            <div class="timeline-container">
                                
                                @php
                                    $trackingSteps = collect();
                                    
                                    // Step 1: Dikirim
                                    $logDikirim = $data->logs->where('aksi', 'PENGIRIMAN_USULAN')->first();
                                    $trackingSteps->push((object)[
                                        'date' => $logDikirim ? $logDikirim->created_at : $data->created_at,
                                        'title' => 'Surat Usulan Dikirim',
                                        'desc' => 'Usulan mutasi berhasil dikirim ke BKPSDM via sistem.',
                                        'icon' => 'fa-paper-plane',
                                        'color' => 'primary'
                                    ]);

                                    // Step 2: Diterima (Disposisi oleh Kepala BKPSDM)
                                    $logDiterima = $data->logs->where('aksi', 'DISPOSISI_SURAT')->sortBy('created_at')->first();
                                    if ($data->disposisi >= 1 || $logDiterima) {
                                        $trackingSteps->push((object)[
                                            'date' => $logDiterima ? $logDiterima->created_at : $data->updated_at,
                                            'title' => 'Surat Usulan Diterima',
                                            'desc' => 'Usulan telah diterima dan didisposisi oleh Kepala BKPSDM.',
                                            'icon' => 'fa-clipboard-check',
                                            'color' => 'info'
                                        ]);
                                    }

                                    // Step 3: Diproses (Staf Teknis)
                                    $logDiproses = $data->logs->filter(function($log) {
                                        return str_contains(strtolower($log->keterangan ?? ''), 'staf teknis') ||
                                               str_contains(strtolower($log->status_usulan ?? ''), 'diproses');
                                    })->sortBy('created_at')->first();
                                    if ($data->disposisi >= 2 || $logDiproses) {
                                        $trackingSteps->push((object)[
                                            'date' => $logDiproses ? $logDiproses->created_at : $data->updated_at,
                                            'title' => 'Surat Usulan Diproses',
                                            'desc' => 'Sedang dalam tahap verifikasi teknis oleh Staf BKPSDM.',
                                            'icon' => 'fa-spinner fa-spin-pulse',
                                            'color' => 'warning'
                                        ]);
                                    }

                                    // Tampilkan semua siklus: Ditolak → Revisi (bisa berulang)
                                    $logsDitolak = $data->logs->filter(function($log) {
                                        return in_array($log->status_usulan, ['Ditolak', 'Berkas Ditolak']);
                                    })->sortBy('created_at')->values();
                                    $logsRevisi = $data->logs->where('aksi', 'UPLOAD_REVISI')->sortBy('created_at')->values();

                                    $maxCycles = max($logsDitolak->count(), $logsRevisi->count(), $data->status == 99 ? 1 : 0);
                                    for ($ci = 0; $ci < $maxCycles; $ci++) {
                                        // Log penolakan ke-$ci
                                        if (isset($logsDitolak[$ci])) {
                                            $trackingSteps->push((object)[
                                                'date'  => $logsDitolak[$ci]->created_at,
                                                'title' => 'Berkas Ditolak / Tidak Lengkap',
                                                'desc'  => $logsDitolak[$ci]->keterangan ?? 'Terdapat dokumen yang tidak sesuai.',
                                                'icon'  => 'fa-times-circle',
                                                'color' => 'danger'
                                            ]);
                                        } elseif ($ci === 0 && $data->status == 99) {
                                            $trackingSteps->push((object)[
                                                'date'  => $data->updated_at,
                                                'title' => 'Berkas Ditolak / Tidak Lengkap',
                                                'desc'  => 'Terdapat dokumen yang tidak sesuai. Harap lakukan revisi.',
                                                'icon'  => 'fa-times-circle',
                                                'color' => 'danger'
                                            ]);
                                        }
                                        // Log revisi ke-$ci
                                        if (isset($logsRevisi[$ci])) {
                                            $trackingSteps->push((object)[
                                                'date'  => $logsRevisi[$ci]->created_at,
                                                'title' => 'Revisi Berkas Diunggah',
                                                'desc'  => $logsRevisi[$ci]->keterangan ?? 'PNS mengunggah ulang berkas yang ditolak.',
                                                'icon'  => 'fa-upload',
                                                'color' => 'warning'
                                            ]);
                                        }
                                    }

                                    // Menunggu verifikasi ulang (setelah revisi, sebelum diverifikasi lagi)
                                    if ($logsRevisi->count() > 0 && $data->status == 3) {
                                        $trackingSteps->push((object)[
                                            'date'  => $logsRevisi->last()->created_at,
                                            'title' => 'Menunggu Verifikasi Ulang',
                                            'desc'  => 'Berkas revisi diterima. Sedang ditinjau kembali oleh Staf BKPSDM.',
                                            'icon'  => 'fa-clock-rotate-left',
                                            'color' => 'info'
                                        ]);
                                    }

                                    // Step Selesai
                                    $logSelesai = $data->logs->where('aksi', 'VERIFIKASI_SELESAI')->sortBy('created_at')->first();
                                    if (in_array($data->status, [4, 5]) || $logSelesai) {
                                        $trackingSteps->push((object)[
                                            'date'  => $logSelesai ? $logSelesai->created_at : $data->updated_at,
                                            'title' => 'Usulan Selesai Diproses',
                                            'desc'  => 'SK Mutasi telah diterbitkan. Proses pengajuan selesai.',
                                            'icon'  => 'fa-check-double',
                                            'color' => 'success'
                                        ]);
                                    }
                                @endphp
                                
                                @foreach($trackingSteps as $step)
                                <div class="timeline-item">
                                    <div class="timeline-icon bg-{{ $step->color }} text-white shadow-sm {{ $loop->last && $data->status != 0 && $data->status < 4 ? 'border border-3 border-white' : '' }}">
                                        <i class="align-middle fa {{ $step->icon }}"></i>
                                    </div>
                                    <div class="timeline-card {{ !$loop->last ? 'pb-4' : '' }}">
                                        <div class="timeline-date d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($step->date)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="timeline-body mt-1">
                                            <div class="timeline-title text-{{ $step->color }} fw-bold">{{ $step->title }}</div>
                                            <p class="mb-0 text-muted small mt-1">{{ $step->desc }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
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
                            @forelse($data->pesans ?? [] as $pesan)
                                @if($pesan->id_user == auth()->id())
                                    <!-- Pesan Pengirim (Kanan) -->
                                    <div class="d-flex justify-content-end mb-3">
                                        <div class="bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 75%;">
                                            <p class="mb-1 small">{{ $pesan->pesan }}</p>
                                            <small class="text-white-50 float-end" style="font-size: 0.70rem;">{{ $pesan->created_at->format('d/m H:i') }}</small>
                                        </div>
                                    </div>
                                @else
                                    <!-- Pesan Penerima / Admin (Kiri) -->
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
                            <input type="hidden" name="id_usulan" value="{{ $data->id_usulan }}">
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
        .timeline-container { position: relative; padding: 10px 0; }
        .timeline-item { position: relative; margin-bottom: 0px; }
        .timeline-item::before { content: ""; position: absolute; left: 19px; top: 0; bottom: 0; width: 2px; background: #e0e0e0; z-index: 1; }
        .timeline-item:last-child::before { display: none; }
        .timeline-icon { position: absolute; left: 0; top: 0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; border: 4px solid white; }
        .timeline-date { font-weight: 600; font-size: 0.85rem; color: #6c757d; margin-bottom: 5px; }
        .timeline-title { font-weight: bold; margin-bottom: 5px; font-size: 1.1rem; }
        .timeline-card { padding-left: 60px; min-height: 80px; }
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
