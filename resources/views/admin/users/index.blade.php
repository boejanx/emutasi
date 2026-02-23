<x-app-layout>
    <div class="container-fluid p-0">
        <div class="row mb-2 mb-xl-3">
            <div class="col-auto d-none d-sm-block">
                <h3><strong>Pengaturan</strong> Pengguna Sistem</h3>
            </div>
            <div class="col-auto ms-auto text-end mt-n1">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fa fa-plus me-1"></i> Tambah Pengguna Terdaftar
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
                                    <th>Nama Pengguna</th>
                                    <th>Email</th>
                                    <th>Jabatan / Role</th>
                                    <th>Terdaftar Pada</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 0) <span class="badge bg-primary">Pimpinan</span>
                                        @elseif($user->role == 1) <span class="badge bg-danger">Admin BKPSDM</span>
                                        @elseif($user->role == 2) <span class="badge bg-info">Admin OPD</span>
                                        @elseif($user->role == 3) <span class="badge bg-success">PNS Biasa</span>
                                        @elseif($user->role == 4) <span class="badge bg-warning text-dark">Kepala Bidang</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.manage-users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Semua data terkait mungkin akan hilang.');">
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
    @foreach($users as $user)
    <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Pengguna: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.manage-users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email / NIP Login</label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Akses</label>
                            <select class="form-select" name="role" required>
                                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>0 - Pimpinan (Kepala BKPSDM)</option>
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>1 - Admin BKPSDM</option>
                                <option value="4" {{ $user->role == 4 ? 'selected' : '' }}>4 - Kepala Bidang</option>
                                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>2 - Admin Instansi / OPD</option>
                                <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>3 - PNS Biasa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reset Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
                            <input type="password" class="form-control" name="password" minlength="8">
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
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.manage-users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Cth: Dr. Joko, M.Si">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Akses <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="Cth: admin@pekalongankab.go.id">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Jabatan <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="">- Pilih Role -</option>
                                <option value="0">Pimpinan Kepala Badan</option>
                                <option value="4">Kepala Bidang Teknis</option>
                                <option value="1">Admin Verifikator BKPSDM</option>
                                <option value="2">Admin Sub Kepegawaian Instansi OPD</option>
                                <option value="3">Pegawai Negeri Sipil Biasa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Sementara <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required minlength="8" placeholder="Minimal 8 karakter">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Daftarkan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
