<x-app-layout>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Profil Pengguna</h1>

        <div class="row">
            <div class="col-md-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Akun Utama</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 text-center">
                            <i class="fa fa-user-circle fa-5x text-secondary mb-3"></i>
                            <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                        </div>

                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP (Nomor Induk Pegawai)</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                                <small class="text-muted">NIP digunakan sebagai username untuk login ke dalam sistem.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                            </div>
                        </form>

                        <hr>

                        <div class="alert alert-info mt-4 p-3" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="me-3 mt-1">
                                    <i class="fa fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="alert-heading">Kebijakan Pembaruan Data</h4>
                                    <p class="mb-2">Perubahan data profil (termasuk kata sandi/password) terintegrasi secara terpusat. Untuk melakukan perubahan, silakan akses aplikasi <strong>POLAKESATU</strong> menggunakan portal manajemen akun profil SSO Anda.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="https://polakesatu.pekalongankab.go.id" target="_blank" class="btn btn-primary btn-lg mt-2 shadow-sm rounded-pill">
                                <i class="fa fa-external-link-alt me-2"></i> Buka Aplikasi POLAKESATU
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
