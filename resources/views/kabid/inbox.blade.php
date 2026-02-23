<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">INBOX USULAN MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Daftar Surat Usulan Masuk (Bidang Mutasi)</h5>
                        <h6 class="card-subtitle text-muted mt-2">Daftar berkas usulan yang masuk ke meja Kepala Bidang untuk diteruskan ke proses teknis.</h6>
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
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm btn-disposisi" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalDisposisi-{{ $usulan->id_usulan }}">
                                                <i class="fa fa-share"></i> Proses Disposisi
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
                <form action="{{ route('kabid.disposisi.store', $usulan->id_usulan) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title h4"><i class="align-middle me-2" data-feather="share-2"></i> Detail & Disposisi Terusan</h5>
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

                        <hr>

                        <h5 class="h6 mb-3 mt-4"><i class="align-middle me-1" data-feather="edit-2"></i> Tujuan & Catatan Kepala Bidang</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Teruskan Kepada: <span class="text-danger">*</span></label>

                            <label class="form-check p-3 border rounded border-primary bg-light mb-2 custom-radio-box">
                                <input class="form-check-input ms-0 mt-1" type="radio" value="2" name="tujuan_disposisi" checked required>
                                <span class="form-check-label ms-4 d-block">
                                    <strong>Staf Teknis (Tim Verifikator Bidang)</strong><br>
                                    <small class="text-muted">Teruskan berkas untuk mulai dilakukan validasi dan proses teknis penerbitan SK.</small>
                                </span>
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Kepala Bidang (Arahan/Komentar):</label>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Ketik arahan atau catatan verifikasi penting disini..." style="resize: none;"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan & Teruskan</button>
                    </div>
                </form>
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
        });
    </script>
</x-app-layout>
