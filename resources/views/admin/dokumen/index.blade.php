<x-app-layout>
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Kelola</strong> Berkas Persyaratan Mutasi</h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDokumenModal">
                    <i class="fa fa-plus me-1"></i> Tambah Persyaratan Baru
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <table class="table table-hover table-striped my-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Berkas Dokumen</th>
                                    <th>Wajib Diunggah</th>
                                    <th>Status Dokumen</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumen as $index => $dok)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $dok->nama_dokumen }}</strong></td>
                                    <td>
                                        @if($dok->wajib)
                                            <span class="badge bg-danger"><i class="fa fa-asterisk"></i> Ya, Wajib</span>
                                        @else
                                            <span class="badge bg-secondary">Opsional</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dok->status)
                                            <span class="badge bg-success"><i class="fa fa-check-circle"></i> Aktif</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa fa-times-circle"></i> Tidak Berlaku</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editDokumenModal-{{ $dok->id_dokumen }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.manage-dokumen.destroy', $dok->id_dokumen) }}" method="POST" class="d-inline" onsubmit="return confirm('Peringatan: Menghapus syarat dokumen mungkin merusak integritas relasi file pada sistem. Lebih aman untuk mengubah statusnya menjadi Tidak Berlaku. Yakin ingin menghapus secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($dokumen as $index => $dok)
    <div class="modal fade" id="editDokumenModal-{{ $dok->id_dokumen }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Berkas: {{ Str::limit($dok->nama_dokumen, 30) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.manage-dokumen.update', $dok->id_dokumen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Dokumen Persyaratan</label>
                            <input type="text" class="form-control" name="nama_dokumen" value="{{ $dok->nama_dokumen }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apakah PNS Wajib Mengunggah Ini?</label>
                            <select class="form-select" name="wajib" required>
                                <option value="1" {{ $dok->wajib == 1 ? 'selected' : '' }}>Ya, Dokumen Wajib</option>
                                <option value="0" {{ $dok->wajib == 0 ? 'selected' : '' }}>Tidak, Dokumen Opsional</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Pemberlakuan</label>
                            <select class="form-select" name="status" required>
                                <option value="1" {{ $dok->status == 1 ? 'selected' : '' }}>Berlaku / Aktif Minta dari PNS</option>
                                <option value="0" {{ $dok->status == 0 ? 'selected' : '' }}>Diskip / Tidak Aktif Diminta</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Modal -->
    <div class="modal fade" id="addDokumenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Berkas Persyaratan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.manage-dokumen.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_dokumen" required placeholder="Cth: Fotokopi SK CPNS">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sifat Kepentingan <span class="text-danger">*</span></label>
                            <select class="form-select" name="wajib" required>
                                <option value="1">Wajib (Harus Diunggah)</option>
                                <option value="0">Dokumen Opsional / Pendukung</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Kelayakan <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="1">Aktif Ditetapkan sebagai Persyaratan</option>
                                <option value="0">Draft / Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Syarat Dokumen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
