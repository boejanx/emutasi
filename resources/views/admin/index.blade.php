<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">INBOX USULAN MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Daftar Surat Usulan Masuk (Staf Teknis / Verifikator)</h5>
                        <h6 class="card-subtitle text-muted mt-2">Daftar berkas usulan yang masuk ke meja Staf untuk diverifikasi dan diterbitkan SK.</h6>
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
                                            @if($usulan->status == 4)
                                                <span class="badge bg-primary"><i class="fa fa-file-export"></i> Menunggu Upload SK</span>
                                            @elseif($usulan->disposisi == 0)
                                                <span class="badge bg-warning text-dark"><i class="fa fa-user-tie"></i> Menunggu Kepala BKPSDM</span>
                                            @elseif($usulan->disposisi == 1)
                                                <span class="badge bg-info text-dark"><i class="fa fa-user-tie"></i> Menunggu Kepala Bidang</span>
                                            @elseif($usulan->disposisi == 2)
                                                <span class="badge bg-success"><i class="fa fa-users-cog"></i> Staf Teknis (Verifikasi)</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usulan->status == 4)
                                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalUploadSK-{{ $usulan->id_usulan }}">
                                                    <i class="fa fa-upload"></i> Upload SK Mutasi
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm btn-disposisi" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDisposisi-{{ $usulan->id_usulan }}">
                                                    <i class="fa fa-pen-to-square"></i> Verifikasi Berkas
                                                </button>
                                            @endif
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
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.verifikasi.store', $usulan->id_usulan) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title h4"><i class="align-middle me-2" data-feather="check-square"></i> Verifikasi Usulan Mutasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="alert alert-primary p-2 mb-4">
                            <i class="align-middle me-2" data-feather="file-text"></i> <strong>Surat Nomor:</strong> {{ $usulan->no_surat }}
                        </div>

                        <h5 class="h6 mb-3"><i class="align-middle me-1" data-feather="clock"></i> Riwayat Usulan & Disposisi</h5>
                        <ul class="mb-4" style="list-style-type: none; padding-left: 0;">
                            @forelse($usulan->logs as $log)
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            @if(str_contains(strtolower($log->aksi), 'disposisi') || str_contains(strtolower($log->aksi), 'teruskan'))
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-share"></i></div>
                                            @else
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fa fa-check"></i></div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 border rounded p-2">
                                            <strong>{{ $log->aksi }}</strong> &bull; <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small><br />
                                            <small class="text-secondary"><i class="fa fa-user" style="width: 12px; height: 12px;"></i> {{ $log->user->name ?? 'Sistem' }}</small>
                                            @if($log->status_usulan)
                                                <span class="badge bg-info ms-1">{{ $log->status_usulan }}</span>
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

                        <h5 class="h6 mb-3 mt-4"><i class="align-middle me-1" data-feather="file-text"></i> Verifikasi Dokumen Lampiran</h5>
                        <p class="small text-muted mb-2">Silakan periksa dan verifikasi masing-masing kelengkapan berkas berikut. Anda harus menentukan status (Sesuai/Tidak) pada tiap berkas sebelum memutuskan hasil verifikasi akhir.</p>
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Dokumen</th>
                                        <th class="text-center" style="width: 100px;">File</th>
                                        <th class="text-center" style="width: 250px;">Aksi Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hasBerkas = false; @endphp
                                    @if(isset($usulan->details) && count($usulan->details) > 0)
                                        @foreach($usulan->details->first()->berkas ?? [] as $berkas)
                                            @php $hasBerkas = true; @endphp
                                            <tr>
                                                <td class="small fw-bold">{{ $berkas->dokumen->nama_dokumen ?? 'Dokumen Syarat' }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info text-white view-pdf-btn" data-url="{{ asset('storage/' . $berkas->path_dokumen) }}" data-target="#pdf-container-{{ $berkas->id_berkas }}">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <input type="radio" class="btn-check" name="berkas_status[{{ $berkas->id_berkas }}]" id="berkas-{{ $berkas->id_berkas }}-terima" value="terima" autocomplete="off" required>
                                                        <label class="btn btn-outline-success btn-sm" for="berkas-{{ $berkas->id_berkas }}-terima"><i class="fa fa-check"></i> Sesuai</label>
                                                        
                                                        <input type="radio" class="btn-check berkas-radio-tolak" name="berkas_status[{{ $berkas->id_berkas }}]" id="berkas-{{ $berkas->id_berkas }}-tolak" value="tolak" autocomplete="off" required data-nama="{{ $berkas->dokumen->nama_dokumen ?? 'Dokumen' }}">
                                                        <label class="btn btn-outline-danger btn-sm" for="berkas-{{ $berkas->id_berkas }}-tolak"><i class="fa fa-times"></i> Tidak</label>
                                                    </div>
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
                                    @endif
                                    
                                    @if(!$hasBerkas)
                                        <tr>
                                            <td colspan="3" class="text-center small py-3 text-danger">Tidak ada berkas yang dilampirkan.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <!-- Start Panel Pesan / Helpdesk Read Only -->
                        <h5 class="h6 mb-3 mt-4"><i class="align-middle me-1 fa fa-comments text-primary"></i> Percakapan / Catatan Revisi PNS</h5>
                        <div class="card shadow-sm border mb-4">
                            <div class="card-body bg-light-50 p-3">
                                <div class="chat-container" style="max-height: 250px; overflow-y: auto;">
                                    @forelse($usulan->pesans ?? [] as $pesan)
                                        @if($pesan->id_user == auth()->id())
                                            <div class="d-flex justify-content-end mb-3">
                                                <div class="bg-primary text-white p-2 rounded-3 shadow-sm" style="max-width: 85%;">
                                                    <p class="mb-1 small">{{ $pesan->pesan }}</p>
                                                    <small class="text-white-50 float-end" style="font-size: 0.70rem;">{{ $pesan->created_at->format('d/m H:i') }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-start mb-3">
                                                <div class="bg-white border p-2 rounded-3 shadow-sm" style="max-width: 85%;">
                                                    <div class="fw-bold small text-primary mb-1">{{ $pesan->user->name ?? 'Pengguna' }}</div>
                                                    <p class="mb-1 small text-dark">{{ $pesan->pesan }}</p>
                                                    <small class="text-muted float-end" style="font-size: 0.70rem;">{{ $pesan->created_at->format('d/m H:i') }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center text-muted my-3">
                                            <p class="small mb-0 fst-italic">Belum ada percakapan atau pertanyaan terkait dokumen ini.</p>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="mt-2 text-end">
                                    <a href="{{ route('admin.tracking.detail', $usulan->id_usulan) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fa fa-external-link-alt me-1"></i> Buka Halaman Diskusi Penuh</a>
                                </div>
                            </div>
                        </div>
                        <!-- End Panel Pesan -->

                        <hr>

                        <h5 class="h6 mb-3 mt-4"><i class="align-middle me-1" data-feather="edit-2"></i> Hasil Verifikasi Akhir & Catatan</h5>

                        <div class="mb-3 final-verifikasi-container">
                            <label class="form-label fw-bold">Status Verifikasi Usulan: <span class="text-danger">*</span></label>

                            <label class="form-check p-2 border rounded border-success bg-light mb-2 custom-radio-box final-radio-terima" style="opacity: 0.5; pointer-events: none;">
                                <input class="form-check-input ms-0 mt-2" type="radio" value="terima" name="status_verifikasi" required disabled>
                                <span class="form-check-label ms-4 d-block">
                                    <strong class="text-success">Berkas Lengkap & Setuju Terbitkan SK</strong><br>
                                    <small class="text-muted">Otomatis terpilih jika seluruh berkas Sesuai.</small>
                                </span>
                            </label>

                            <label class="form-check p-2 border rounded border-warning bg-light mb-2 custom-radio-box final-radio-revisi" style="opacity: 0.5; pointer-events: none;">
                                <input class="form-check-input ms-0 mt-2" type="radio" value="revisi" name="status_verifikasi" required disabled>
                                <span class="form-check-label ms-4 d-block">
                                    <strong class="text-warning text-dark">Berkas Perlu Direvisi</strong><br>
                                    <small class="text-muted">Otomatis terpilih jika ada berkas yang Tidak Sesuai. PNS bisa memperbaiki.</small>
                                </span>
                            </label>

                            <label class="form-check p-2 border rounded border-danger bg-light mb-2 custom-radio-box final-radio-tolak" style="opacity: 0.5; pointer-events: none;">
                                <input class="form-check-input ms-0 mt-2" type="radio" value="tolak" name="status_verifikasi" required disabled>
                                <span class="form-check-label ms-4 d-block">
                                    <strong class="text-danger">Tolak Permanen</strong><br>
                                    <small class="text-muted">Pilih ini secara manual jika usulan mutasi ditolak mutlak (PNS tidak bisa merevisi).</small>
                                </span>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Verifikasi (Wajib jika ditolak):</label>
                            <textarea class="form-control catatan-textarea" name="catatan" rows="3" placeholder="Ketik alasan penolakan atau catatan verifikasi penting disini..." style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Hasil Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Modal Upload SK Mutasi --}}
    @foreach($usulans as $usulan)
        @if($usulan->status == 4)
        <div class="modal fade" id="modalUploadSK-{{ $usulan->id_usulan }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.upload-sk', $usulan->id_usulan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-success">
                            <h5 class="modal-title h4 text-white"><i class="align-middle me-2" data-feather="upload"></i> Upload SK Mutasi</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body m-3">
                            <div class="alert alert-info py-2 small">
                                <strong>Info:</strong> Berkas verifikasi telah dinyatakan Lengkap. Silakan unggah dokumen SK Mutasi yang telah ditandatangani untuk menyelesaikan proses.
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor SK Mutasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nomor_sk" placeholder="Contoh: 800/123/BKPSDM/2024" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal SK <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_sk" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold">TMT Jabatan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tmt_jabatan" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">TMT Pelantikan <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tmt_pelantikan" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">File SK Mutasi (PDF) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file_sk" accept=".pdf" required>
                                <div class="form-text small">Format: PDF, Maks 2MB.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Selesaikan & Terbitkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
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

            // Logic for file verification dependency
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const berkasRadios = form.querySelectorAll('input[name^="berkas_status"]');
                if (berkasRadios.length === 0) return; // Skip if no files

                const radioTerima = form.querySelector('input[name="status_verifikasi"][value="terima"]');
                const radioRevisi = form.querySelector('input[name="status_verifikasi"][value="revisi"]');
                const radioTolak = form.querySelector('input[name="status_verifikasi"][value="tolak"]');
                
                const labelTerima = form.querySelector('.final-radio-terima');
                const labelRevisi = form.querySelector('.final-radio-revisi');
                const labelTolak = form.querySelector('.final-radio-tolak');

                function updateFinalStatus() {
                    const names = new Set();
                    berkasRadios.forEach(r => names.add(r.name));

                    let allChecked = true;
                    let hasTolak = false;

                    names.forEach(name => {
                        const checked = form.querySelector(`input[name="${name}"]:checked`);
                        if (!checked) {
                            allChecked = false;
                        } else if (checked.value === 'tolak') {
                            hasTolak = true;
                        }
                    });

                    if (allChecked) {
                        labelTerima.style.opacity = '1';
                        labelRevisi.style.opacity = '1';
                        labelTolak.style.opacity = '1';
                        
                        radioTerima.disabled = false;
                        radioRevisi.disabled = false;
                        radioTolak.disabled = false;

                        // Enable manual clicking on the container labels now
                        labelTerima.style.pointerEvents = 'auto';
                        labelRevisi.style.pointerEvents = 'auto';
                        labelTolak.style.pointerEvents = 'auto';
                        
                        // We only auto-select upon initial all-checked or if we want to force it
                        // But let's keep it simple: auto update if they are just checking boxes
                        if (hasTolak) {
                            radioRevisi.checked = true;
                            labelTerima.style.opacity = '0.5';
                        } else {
                            radioTerima.checked = true;
                            labelRevisi.style.opacity = '0.5';
                            labelTolak.style.opacity = '0.5';
                        }

                    } else {
                        // Still incomplete
                        radioTerima.disabled = true;
                        radioTerima.checked = false;
                        radioRevisi.disabled = true;
                        radioRevisi.checked = false;
                        radioTolak.disabled = true;
                        radioTolak.checked = false;
                        
                        labelTerima.style.opacity = '0.5';
                        labelRevisi.style.opacity = '0.5';
                        labelTolak.style.opacity = '0.5';
                        
                        labelTerima.style.pointerEvents = 'none';
                        labelRevisi.style.pointerEvents = 'none';
                        labelTolak.style.pointerEvents = 'none';
                    }

                    // AUTO-FILL CATATAN based on rejected items
                    const rejectedItems = [];
                    names.forEach(name => {
                        const checked = form.querySelector(`input[name="${name}"]:checked`);
                        if (checked && checked.value === 'tolak') {
                            rejectedItems.push(checked.getAttribute('data-nama'));
                        }
                    });

                    const catatanTextarea = form.querySelector('.catatan-textarea');
                    if (rejectedItems.length > 0) {
                        catatanTextarea.value = "Revisi berkas: " + rejectedItems.join(", ");
                    } else {
                        catatanTextarea.value = "";
                    }
                }

                berkasRadios.forEach(r => {
                    r.addEventListener('change', updateFinalStatus);
                });
            });

            // Logic for inline PDF viewer
            document.querySelectorAll('.view-pdf-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah reload
                    const url = this.getAttribute('data-url');
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.querySelector(targetId);
                    
                    if (!targetRow) return;
                    
                    const iframe = targetRow.querySelector('iframe');
                    
                    if (targetRow.classList.contains('show')) {
                        // Jika sedang tampil, sembunyikan
                        targetRow.classList.remove('show');
                        setTimeout(() => {
                            iframe.src = '';
                        }, 300); // clear iframe src after collapse animation
                        this.innerHTML = '<i class="fa fa-eye"></i> Lihat';
                        this.classList.replace('btn-secondary', 'btn-info');
                    } else {
                        // Jika sedang sembunyi, tampilkan iframe
                        iframe.src = url;
                        // Use bootstrap collapse API or simple class toggle
                        targetRow.classList.add('show');
                        this.innerHTML = '<i class="fa fa-eye-slash"></i> Tutup Dokumen';
                        this.classList.replace('btn-info', 'btn-secondary');
                    }
                });
            });
        });
    </script>
</x-app-layout>
