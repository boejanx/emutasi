<x-guest-layout>
    <div class="d-flex w-100 h-100 min-vh-100">
        <!-- Left half: Image/Branding -->
        <div class="col-12 col-md-6 col-lg-7 d-none d-md-flex flex-column justify-content-between text-white" 
             style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.9) 100%), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80') center/cover no-repeat;">
            <div class="p-5">
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="width: 55px; height: 55px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" onerror="this.src='https://ui-avatars.com/api/?name=K+P&color=1E3C72&background=ffffff'">
                    <h3 class="ms-3 mb-0 fw-bold text-white" style="letter-spacing: 0.5px;">e-Mutasi</h3>
                </div>
                <h1 class="display-4 fw-bolder mt-5 mb-3 text-white" style="line-height: 1.2;">Sistem Informasi<br>Mutasi Pegawai</h1>
                <p class="lead fw-normal text-white-50 mt-4" style="max-width: 500px; font-size: 1.1rem; line-height: 1.6;">
                    Portal terpadu untuk pengajuan dan pemantauan usulan mutasi PNS secara elektronik di lingkungan Pemerintah Kabupaten Pekalongan.
                </p>
            </div>
            
            <div class="p-5">
                <div class="row text-center text-md-start">
                    <div class="col-sm-4 mb-3 mb-sm-0">
                        <h2 class="fw-bolder mb-1 text-white">Akurat</h2>
                        <small class="text-white-50 text-uppercase tracking-wide" style="letter-spacing: 1px;">Terintegrasi</small>
                    </div>
                    <div class="col-sm-4 mb-3 mb-sm-0 border-start border-light-subtle">
                        <h2 class="fw-bolder mb-1 text-white">Praktis</h2>
                        <small class="text-white-50 text-uppercase tracking-wide" style="letter-spacing: 1px;">Tanpa Kertas</small>
                    </div>
                    <div class="col-sm-4 border-start border-light-subtle">
                        <h2 class="fw-bolder mb-1 text-white">Efektif</h2>
                        <small class="text-white-50 text-uppercase tracking-wide" style="letter-spacing: 1px;">Lebih Cepat</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right half: Login Form -->
        <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center justify-content-center bg-white shadow-lg z-1">
            <div class="w-100 px-4 px-md-5 px-xl-6 py-5" style="max-width: 520px;">
                <div class="text-center mb-5 d-md-none">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;" onerror="this.style.display='none'">
                    <h3 class="mt-3 fw-bold text-primary">e-Mutasi</h3>
                    <p class="text-muted">BKPSDM Kab. Pekalongan</p>
                </div>
                
                <div class="mb-5 text-center text-md-start">
                    <h2 class="fw-bold text-dark" style="font-size: 2rem;">Selamat Datang 👋</h2>
                    <p class="text-secondary mt-2">Silakan masuk menggunakan NIP dan Password akun Anda untuk melanjutkan.</p>
                </div>
                
                <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate id="loginForm">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger d-flex align-items-center mb-4 rounded-3 p-3 shadow-sm">
                            <i class="fa fa-exclamation-circle fs-4 me-3"></i>
                            <div>
                                <strong class="d-block mb-1">Peringatan:</strong>
                                <small>{{ $errors->first() }}</small>
                            </div>
                        </div>
                    @endif
                    
                    <div class="form-floating mb-4 position-relative">
                        <input type="text" class="form-control px-4 fw-semibold" id="email" name="email" value="{{ old('email') }}" placeholder="NIP/Username" style="height: 65px; border-radius: 12px; border: 1px solid #dee2e6; background-color: #fafbfc; transition: all 0.2s;" required autofocus>
                        <label for="email" class="text-muted px-4 d-flex align-items-center"><i class="fa fa-user me-2 text-primary"></i> Username / NIP</label>
                        <div class="invalid-feedback text-start small mt-1 ms-2" style="font-weight: 500;">
                            <i class="fa fa-info-circle me-1"></i> Username wajib diisi
                        </div>
                    </div>
                    
                    <div class="form-floating mb-4 position-relative">
                        <input type="password" class="form-control px-4 fw-semibold" id="password" name="password" placeholder="Password" style="height: 65px; border-radius: 12px; border: 1px solid #dee2e6; background-color: #fafbfc; transition: all 0.2s;" required autocomplete="current-password">
                        <label for="password" class="text-muted px-4 d-flex align-items-center"><i class="fa fa-lock me-2 text-primary"></i> Password</label>
                        <div class="invalid-feedback text-start small mt-1 ms-2" style="font-weight: 500;">
                            <i class="fa fa-info-circle me-1"></i> Password wajib diisi
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="form-check custom-checkbox">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked style="cursor: pointer; width: 1.2rem; height: 1.2rem;">
                            <label class="form-check-label text-secondary ms-2 mt-1" for="rememberMe" style="cursor: pointer;">
                                Ingat Perangkat Saya
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow position-relative overflow-hidden login-btn" style="height: 60px; border-radius: 12px; font-size: 1.1rem; letter-spacing: 0.5px; transition: all 0.3s ease;">
                        <span class="d-flex align-items-center justify-content-center h-100">
                            Masuk ke Sistem <i class="fa fa-arrow-right ms-2 mt-1 transition-icon"></i>
                        </span>
                    </button>
                    
                    <div class="text-center mt-5 pt-3 border-top">
                        <p class="small text-muted mb-1 d-flex align-items-center justify-content-center">
                            <i class="fa fa-shield-alt text-success me-2"></i> SSO Terintegrasi dengan Polakesatu
                        </p>
                        <p class="small text-muted mt-2">&copy; {{ date('Y') }} BKPSDM Kab. Pekalongan.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #fff;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        .form-floating > .form-control:focus ~ label, 
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(0.85) translateY(-1rem) translateX(0.15rem);
            color: #3b7ddd !important;
            font-weight: 600;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(59, 125, 221, 0.15);
            background-color: #fff !important;
            border-color: #3b7ddd !important;
        }
        .bg-danger-subtle {
            background-color: #f8d7da !important;
        }
        .border-light-subtle {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 125, 221, 0.3) !important;
        }
        .login-btn:active {
            transform: translateY(0);
        }
        .login-btn:hover .transition-icon {
            transform: translateX(5px);
        }
        .transition-icon {
            transition: transform 0.3s ease;
        }
        
        /* Memastikan tidak ada padding header/footer bawaan layout masuk ke page ini */
        .wrapper, .main {
            padding: 0 !important;
            margin: 0 !important;
        }
        nav, footer, .sidebar {
            display: none !important;
        }
        
        /* Checkbox styling */
        .form-check-input:checked {
            background-color: #3b7ddd;
            border-color: #3b7ddd;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch the form we want to apply custom Bootstrap validation styles to
            var form = document.getElementById('loginForm');
            var btnSubmit = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Add shake animation if invalid
                    btnSubmit.classList.add('shake');
                    setTimeout(() => btnSubmit.classList.remove('shake'), 500);
                }
                
                form.classList.add('was-validated');
            }, false);

            // Realtime validation visual improvement
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        // only show red if it has was-validated
                        if(form.classList.contains('was-validated')){
                             this.classList.add('is-invalid');
                             this.classList.remove('is-valid');
                        }
                    }
                });
            });
        });
    </script>
    <style>
        @keyframes shake {
            0%, 100% {transform: translateX(0);}
            10%, 30%, 50%, 70%, 90% {transform: translateX(-5px);}
            20%, 40%, 60%, 80% {transform: translateX(5px);}
        }
        .shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
    </style>
</x-guest-layout>
