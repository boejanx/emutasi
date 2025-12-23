<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" class="mb-4" />

    <div class="page-wrapper" data-header-position="fixed" data-layout="vertical" data-navbarbg="skin6" data-sidebar-position="fixed" data-sidebartype="full" id="main-wrapper">
        <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a class="text-nowrap logo-img text-center d-block py-3 w-100" href="./index.html">
                                    <img alt="" src="../assets/img/logo.png " style="max-width: 150px;">
                                </a>
                                <p class="text-center">e-Mutasi</p>
                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Login gagal!</strong><br> {{ $errors->first() }}
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Username/NIP</label>
                                        <input class="form-control" id="email" name="email" type="email">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control" id="password" name="password" type="password">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input checked class="form-check-input primary" id="flexCheckChecked" name="remember" type="checkbox" value="">
                                            <label class="form-check-label text-dark" for="flexCheckChecked">
                                                Ingat Saya
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" type="submit">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
