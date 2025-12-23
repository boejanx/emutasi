<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta content="Aplikasi emutasi" name="description">
    <meta content="Trifwal" name="author">
    <meta content="mutasi, emutasi, bkpsdm, kabupaten pekalongan" name="keywords">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="img/icons/icon-48x48.png" rel="shortcut icon" />

    <title>e-Mut Kabupaten Pekalongan</title>

    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="wrapper">
        <nav class="sidebar js-sidebar" id="sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <span class="align-middle">E-Mutasi</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="index.html">
                            <i class="align-middle fa fa-home"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

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

                    <li class="sidebar-header">
                        Mutasi
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('opd.index') }}">
                            <i class="align-middle fa fa-square-plus"></i> <span class="align-middle">Pengajuan Baru</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('pns.tracking') }}">
                            <i class="align-middle fa fa-history"></i> <span class="align-middle">Riwayat Pengajuan</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        MUTASI
                    </li>

                    <li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('admin.index') }}">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Usulan Mutasi</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('admin.index') }}">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Usulan Diproses</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="pages-profile.html">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Usulan Selesai</span>
						</a>
					</li>

					<li class="sidebar-header">
                        PENGATURAN
                    </li>

                    <li class="sidebar-item">
						<a class="sidebar-link" href="pages-profile.html">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Akun Pengguna</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="pages-profile.html">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Berkas Persyaratan</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="pages-profile.html">
							<i class="align-middle fa fa-square"></i> <span class="align-middle">Berkas Unduhan</span>
						</a>
					</li>
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
                                    <span class="indicator">4</span>
                                </div>
                            </a>
                            <div aria-labelledby="alertsDropdown" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0">
                                <div class="dropdown-menu-header">
                                    4 New Notifications
                                </div>
                                <div class="list-group">
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-danger" data-feather="alert-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Update completed</div>
                                                <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                                                <div class="text-muted small mt-1">30m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-feather="bell"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Lorem ipsum</div>
                                                <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-feather="home"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Login from 192.186.1.8</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">New connection</div>
                                                <div class="text-muted small mt-1">Christina accepted your request.</div>
                                                <div class="text-muted small mt-1">14h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a class="text-muted" href="#">Show all notifications</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" data-bs-toggle="dropdown" href="#" id="messagesDropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                </div>
                            </a>
                            <div aria-labelledby="messagesDropdown" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        4 New Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img alt="Vanessa Tucker" class="avatar img-fluid rounded-circle" src="img/avatars/avatar-5.jpg">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Vanessa Tucker</div>
                                                <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                                                <div class="text-muted small mt-1">15m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img alt="William Harris" class="avatar img-fluid rounded-circle" src="img/avatars/avatar-2.jpg">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">William Harris</div>
                                                <div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img alt="Christina Mason" class="avatar img-fluid rounded-circle" src="img/avatars/avatar-4.jpg">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Christina Mason</div>
                                                <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
                                                <div class="text-muted small mt-1">4h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="list-group-item" href="#">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img alt="Sharon Lessman" class="avatar img-fluid rounded-circle" src="img/avatars/avatar-3.jpg">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Sharon Lessman</div>
                                                <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a class="text-muted" href="#">Show all messages</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" data-bs-toggle="dropdown" href="#">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" data-bs-toggle="dropdown" href="#">
                                <img alt="{{ Auth::user()->name }}" class="avatar img-fluid rounded me-1" src="assets/img/avatars/avatar.jpg" /> <span
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

    <script src="assets/js/app.js"></script>
</body>

</html>
