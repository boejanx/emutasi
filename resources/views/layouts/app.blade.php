<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta content="Aplikasi emutasi" name="description">
    <meta content="Trifwal" name="author">
    <meta content="mutasi, emutasi, bkpsdm, kabupaten pekalongan" name="keywords">

    <link href="{{ asset('img/icons/icon-48x48.png') }}" rel="shortcut icon" />

    <title>e-Mut Kabupaten Pekalongan</title>

    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <div class="wrapper">
        <nav class="sidebar js-sidebar" id="sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="d-block w-100 p-0 m-0" href="{{ route('dashboard') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-100 d-block m-0 p-0">
                </a>

                <ul class="sidebar-nav">
                    {{-- Dashboard - visible to all roles --}}
                    <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="align-middle fa fa-home"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    {{-- ============================================= --}}
                    {{-- Surat Masuk - Role 0 (Pimpinan) only --}}
                    {{-- ============================================= --}}
                    @if(Auth::user()->role == 0)
                    <li class="sidebar-header">
                        Surat Masuk
                    </li>

                    <li class="sidebar-item {{ request()->is('pimpinan/inbox*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pimpinan.inbox') }}">
                            <i class="align-middle fa fa-inbox"></i> <span class="align-middle">Inbox Usulan</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('pimpinan.riwayat') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pimpinan.riwayat') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Disetujui</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.laporan') }}">
                            <i class="align-middle fa fa-print"></i> <span class="align-middle">Laporan & Rekap</span>
                        </a>
                    </li>
                    @endif

                    {{-- ============================================= --}}
                    {{-- Surat Masuk - Role 4 (Kepala Bidang) only --}}
                    {{-- ============================================= --}}
                    @if(Auth::user()->role == 4)
                    <li class="sidebar-header">
                        Mutasi Bidang
                    </li>

                    <li class="sidebar-item {{ request()->is('kabid/inbox*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('kabid.inbox') }}">
                            <i class="align-middle fa fa-inbox"></i> <span class="align-middle">Inbox Disposisi</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('kabid.riwayat') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('kabid.riwayat') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Disetujui</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.laporan') }}">
                            <i class="align-middle fa fa-print"></i> <span class="align-middle">Laporan Rekapitulasi</span>
                        </a>
                    </li>
                    @endif

                    {{-- ============================================= --}}
                    {{-- Mutasiku - Role 3 (User Biasa) only --}}
                    {{-- ============================================= --}}
                    @if(Auth::user()->role == 3)
                    <li class="sidebar-header">
                        Mutasiku
                    </li>

                    <li class="sidebar-item {{ request()->is('pns') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pns.index') }}">
                            <i class="align-middle fa fa-square-plus"></i> <span class="align-middle">Pengajuan Baru</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('pns/tracking') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('pns.tracking') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Pengajuan</span>
                        </a>
                    </li>
                    @endif

                    {{-- ============================================= --}}
                    {{-- Mutasi - Role 2 (Admin Instansi) only --}}
                    {{-- ============================================= --}}
                    @if(Auth::user()->role == 2)
                    <li class="sidebar-header">
                        Mutasi Instansi
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('opd.usulan.create') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('opd.usulan.create') }}">
                            <i class="align-middle fa fa-file-signature"></i> <span class="align-middle">Buat Usulan Baru</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('opd.riwayat') || request()->routeIs('opd.tracking.detail') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('opd.riwayat') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Usulan</span>
                        </a>
                    </li>
                    @endif

                    {{-- ============================================= --}}
                    {{-- MUTASI + PENGATURAN - Role 1 (Admin) only --}}
                    {{-- ============================================= --}}
                    @if(Auth::user()->role == 1)
                    <li class="sidebar-header">
                        MUTASI
                    </li>

                    <li class="sidebar-item {{ request()->is('bkpsdm') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.index') }}">
                            <i class="align-middle fa fa-inbox"></i> <span class="align-middle">Usulan Masuk</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('bkpsdm/tracking*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.tracking') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Usulan & SK</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        PENGATURAN
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('admin.manage-users') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.manage-users') }}">
                            <i class="align-middle fa fa-users"></i> <span class="align-middle">Akun Pengguna</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.manage-dokumen') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.manage-dokumen') }}">
                            <i class="align-middle fa fa-folder-open"></i> <span class="align-middle">Berkas Persyaratan</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('admin.audit-trail') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('admin.audit-trail') }}">
                            <i class="align-middle fa fa-shield-alt"></i> <span class="align-middle">Audit Log Sistem</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" data-bs-toggle="dropdown" href="#" id="alertsDropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="bell"></i>
                                    @if(isset($notifCount) && $notifCount > 0)
                                        <span class="indicator">{{ $notifCount }}</span>
                                    @endif
                                </div>
                            </a>
                            <div aria-labelledby="alertsDropdown" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0">
                                <div class="dropdown-menu-header">
                                    {{ isset($notifCount) && $notifCount > 0 ? $notifCount . ' Notifikasi Baru' : 'Tidak ada notifikasi' }}
                                </div>
                                <div class="list-group">
                                    @if(isset($notifications) && $notifications->count() > 0)
                                        @foreach($notifications as $notif)
                                            <a class="list-group-item" href="{{ Auth::user()->role == 0 ? route('pimpinan.inbox') : (Auth::user()->role == 4 ? route('kabid.inbox') : (Auth::user()->role == 1 ? route('admin.index') : (Auth::user()->role == 2 ? route('opd.riwayat') : route('pns.tracking')))) }}">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-2">
                                                        @if(Auth::user()->role == 0 || Auth::user()->role == 4)
                                                            <i class="text-warning fa fa-envelope fa-2x"></i>
                                                        @elseif(Auth::user()->role == 1)
                                                            <i class="text-primary fa fa-file-signature fa-2x"></i>
                                                        @else
                                                            <i class="{{ $notif->aksi == 'UPLOAD_SK' ? 'text-success fa fa-certificate' : ($notif->aksi == 'VERIFIKASI_DITOLAK' ? 'text-danger fa fa-times-circle' : 'text-primary fa fa-info-circle') }} fa-2x"></i>
                                                        @endif
                                                    </div>
                                                    <div class="col-10 ps-2">
                                                        <div class="text-dark">
                                                            @if(in_array(Auth::user()->role, [0, 1, 4]))
                                                                Usulan Masuk: {{ $notif->no_surat }}
                                                            @else
                                                                {{ $notif->status_usulan }}
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            @if(in_array(Auth::user()->role, [0, 1, 4]))
                                                                {{ Str::limit($notif->perihal, 40) }}
                                                            @else
                                                                {{ Str::limit($notif->keterangan, 40) }}
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="list-group-item text-center text-muted py-3">
                                            Tidak ada pemberitahuan baru hari ini.
                                        </div>
                                    @endif
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a class="text-muted" href="#">Tampil semua pemberitahuan</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" data-bs-toggle="dropdown" href="#" id="messagesDropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                    @if(isset($pesanCount) && $pesanCount > 0)
                                        <span class="indicator bg-primary">{{ $pesanCount }}</span>
                                    @endif
                                </div>
                            </a>
                            <div aria-labelledby="messagesDropdown" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        {{ isset($pesanCount) && $pesanCount > 0 ? $pesanCount . ' Pesan/Catatan Baru' : 'Tidak ada pesan' }}
                                    </div>
                                </div>
                                <div class="list-group">
                                    @if(isset($headerPesans) && $headerPesans->count() > 0)
                                        @foreach($headerPesans as $pesan)
                                            <a class="list-group-item" href="{{ Auth::user()->role == 1 ? route('admin.tracking.detail', $pesan->id_usulan) : (Auth::user()->role == 2 ? route('opd.tracking.detail', $pesan->id_usulan) : route('pns.tracking.detail', $pesan->id_usulan)) }}">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-2">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 35px; height: 35px;">
                                                            <i class="fa fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-10 ps-2">
                                                        <div class="text-dark fw-bold">{{ $pesan->user->name ?? 'User' }}</div>
                                                        <div class="text-muted small mt-1">{{ Str::limit($pesan->pesan, 40) }}</div>
                                                        <div class="text-muted small mt-1">{{ $pesan->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="list-group-item text-center text-muted py-3">
                                            Belum ada percakapan terbaru.
                                        </div>
                                    @endif
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a class="text-muted" href="#">Lihat Semua Pesan</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" data-bs-toggle="dropdown" href="#">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" data-bs-toggle="dropdown" href="#">
                                <img alt="{{ Auth::user()->name }}" class="avatar img-fluid rounded me-1" src="{{ asset('assets/img/avatars/avatar.jpg') }}" /> <span
                                    class="text-dark">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="route('profile.edit')"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                @if(session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: {!! json_encode(session('success')) !!},
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                @endif

                @if(session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: {!! json_encode(session('error')) !!},
                        });
                    </script>
                @endif
                {{ $slot }}
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="https://emut.bkpsdm.pekalongankab.go.id" target="_blank"><strong>eMutasi</strong></a> &copy;
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Bangkompas</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">Polakesatu</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="https://adminkit.io/" target="_blank">BKPSDM</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</body>

</html>
