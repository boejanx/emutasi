<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Manajemen Template SK</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Daftar Template SK Mutasi</h5>
                            <h6 class="card-subtitle text-muted mt-2">Daftar file template Word (.docx) yang bisa digunakan untuk men-generate Draft SK.</h6>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTemplate">
                            <i class="fa fa-plus me-1"></i> Tambah Template
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Template</th>
                                        <th>Status Active</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templates as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $item->nama_template }}</div>
                                            <small class="text-muted"><a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">Lihat File</a></small>
                                        </td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditTemplate-{{ $item->id }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.template-sk.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada template SK ditambahkan.</td>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambahTemplate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.template-sk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Template SK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="mb-3">
                            <label class="form-label">Nama Template</label>
                            <input type="text" class="form-control" name="nama_template" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">File Template (Word .docx)</label>
                            <input type="file" class="form-control" name="file_template" accept=".docx" required>
                            <small class="form-text text-muted">Aplikasi akan me-replace parameter seperti ${nama}, ${nip}, dll di dalam file word ini.</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="isActiveTambah" name="is_active" value="1" checked>
                            <label class="form-check-label" for="isActiveTambah">Jadikan Template Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach($templates as $item)
    <div class="modal fade" id="modalEditTemplate-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.template-sk.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Template SK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="mb-3">
                            <label class="form-label">Nama Template</label>
                            <input type="text" class="form-control" name="nama_template" value="{{ $item->nama_template }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ganti File Template (Word .docx) - Opsional</label>
                            <input type="file" class="form-control" name="file_template" accept=".docx">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="isActiveEdit-{{ $item->id }}" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActiveEdit-{{ $item->id }}">Jadikan Template Aktif</label>
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

</x-app-layout>
