@include('main-panel.layout.header')
<!-- Page -->
<div class="page">
    @php
        use App\Helpers\BerandaUI;
        $web = BerandaUI::web();
    @endphp
    <!-- Main Header-->
    <div class="main-header side-header hor-header">
        <div class="main-container container">
            <div class="main-header-left">
                <a class="main-header-menu-icon" href="javascript:void(0)" id="mainSidebarToggle"><span></span></a>
                <div class="hor-logo">
                    <a class="main-logo" href="{{ route('mainweb.index') }}">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img desktop-logo" alt="logo" style="max-width: 200px;">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img desktop-logo-dark" alt="logo" style="max-width: 200px;">
                    </a>
                </div>
                <div class="mt-2 p-3">
                    <h5 class="title-web">
                        {{ $web->tagline ? $web->tagline : '' }}
                    </h5>
                    <span class="address-web">
                        {{ $web->tagline ? $web->alamat : '' }}
                    </span>
                </div>
            </div>
            <div class="main-header-center">
                <div class="responsive-logo">
                    <a href="{{ route('site.main') }}"><img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="mobile-logo" alt="logo" style="max-width: 120px;"></a>
                    <a href="{{ route('site.main') }}"><img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="mobile-logo-dark" alt="logo" style="max-width: 120px;"></a>
                </div>
            </div>
            <div class="main-header-right">
                <button class="navbar-toggler navresponsive-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
                </button><!-- Navresponsive closed -->
                <div class="navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        <div class="d-flex order-lg-2 ms-auto">
                            <!-- Theme-Layout -->
                            <div class="dropdown d-flex main-header-theme">
                                <a class="nav-link icon layout-setting">
                                    <span class="dark-layout">
                                        <i class="fe fe-sun header-icons"></i>
                                    </span>
                                    <span class="light-layout">
                                        <i class="fe fe-moon header-icons"></i>
                                    </span>
                                </a>
                            </div>
                            <!-- Theme-Layout -->
                            <!-- Full screen -->
                            <div class="dropdown ">
                                <a class="nav-link icon full-screen-link">
                                    <i class="fe fe-maximize fullscreen-button fullscreen header-icons"></i>
                                    <i class="fe fe-minimize fullscreen-button exit-fullscreen header-icons"></i>
                                </a>
                            </div>
                            <!-- Full screen -->
                            <!-- Notification -->
                            <div class="dropdown main-header-notification">
                                <a class="nav-link icon" href="">
                                    <i class="fe fe-bell header-icons"></i>
                                    <span class="badge bg-danger nav-link-badge">{{ $countNotitTryoutGratis }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="header-navheading">
                                        <p class="main-notification-text">Pengajuan Tryout Gratis</p>
                                    </div>
                                    <div class="main-notification-list">
                                        @foreach ($notifTryoutGratis as $notif)
                                            <div class="media new">
                                                <div class="media-body">
                                                    <p>
                                                        Permohonan Baru : {{ $notif->nama_lengkap }} <br>
                                                        <strong>{{ $notif->nama_tryout }}</strong>
                                                    </p>
                                                    <span>{{ $notif->created_at }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="dropdown-footer">
                                        <a class="text-primary"
                                            href="{{ route('tryouts.pengajuan-tryout-gratis') }}">Lihat Semua
                                            Permohonan</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Notification -->
                            <!-- Profile -->
                            <div class="dropdown main-profile-menu">
                                <a class="d-flex" href="javascript:void(0)">
                                    <span class="main-img-user"><img alt="avatar"
                                            src="{{ asset('storage/user/user-default.png') }}"></span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="header-navheading">
                                        <h6 class="main-notification-title">{{ Auth::user()->name }}</h6>
                                        <p class="main-notification-text text-bold">{{ Auth::user()->role }}</p>
                                    </div>
                                    <a class="dropdown-item border-top" href="{{ route('main.profil-admin') }}">
                                        <i class="fe fe-user"></i> Profil Saya
                                    </a>
                                    <form action="{{ route('auth.signout-proses') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="dropdown-item">
                                            <i class="fe fe-power"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- Profile -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Header-->

    <!-- Sidemenu -->
    <div class="sticky">
        <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
            <div class="main-sidebar-header main-container-1 active">
                <div class="sidemenu-logo">
                    <a class="main-logo" href="{{ route('site.main') }}">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img desktop-logo" alt="logo">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img icon-logo" alt="logo">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img desktop-logo theme-logo" alt="logo">
                        <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                            class="header-brand-img icon-logo theme-logo" alt="logo">
                    </a>
                </div>
                <div class="main-sidebar-body main-body-1">
                    <div class="slide-left disabled" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                    <ul class="menu-nav nav">
                        <li class="nav-header"><span class="nav-label">Dashboard</span></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('main.beranda') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-home sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Beranda</span>
                            </a>
                        </li>
                        @if (Auth::user()->role == 'Superadmin')
                            <li class="nav-item">
                                <a class="nav-link with-sub" href="javascript:void(0)">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-user sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Manajemen Pengguna</span>
                                    <i class="angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="nav-sub">
                                    <li class="side-menu-label1"><a href="javascript:void(0)">Submenu</a></li>
                                    <li class="nav-sub-item"><a class="nav-sub-link"
                                            href="{{ route('customer.main') }}">Customer</a>
                                    </li>
                                    <li class="nav-sub-item"><a class="nav-sub-link"
                                            href="{{ route('user.main') }}">Admin & Finance</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link with-sub" href="javascript:void(0)">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-desktop sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Manajemen Tryout</span>
                                <i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="nav-sub">
                                <li class="side-menu-label1"><a href="javascript:void(0)">Submenu</a></li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('tryouts.index') }}">Produk</a>
                                </li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('listOrders.main') }}">List Order
                                    </a>
                                </li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('kategori.index', ['produk' => 'tryout']) }}">Kategori
                                    </a>
                                </li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('klasifikasi.index') }}">Klasifikasi Soal
                                    </a>
                                </li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('tryouts.peserta-tryout') }}">Peserta
                                        Tryout</a>
                                </li>
                                <li class="nav-sub-item"><a class="nav-sub-link"
                                        href="{{ route('tryouts.pengajuan-tryout-gratis') }}">Tryout Gratis</a>
                                </li>
                            </ul>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link with-sub" href="javascript:void(0)">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-calendar sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Manajemen Event</span>
                                <i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="nav-sub">
                                <li class="side-menu-label1"><a href="javascript:void(0)">Submenu</a></li>
                                <li class="nav-sub-item">
                                    <a class="nav-sub-link" href="javascript:void(0)">Tryout</a>
                                </li>
                            </ul>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('referral.main') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-server sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Referral</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('testimoni.main') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-medall sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Testimoni</span>
                            </a>
                        </li>
                        @if (Auth::user()->role == 'Superadmin')
                            <li class="nav-item">
                                <a class="nav-link with-sub" href="javascript:void(0)">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-settings sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Pengaturan</span>
                                    <i class="angle fe fe-chevron-right"></i>
                                </a>
                                <ul class="nav-sub">
                                    <li class="side-menu-label1"><a href="javascript:void(0)">Submenu</a></li>
                                    <li class="nav-sub-item">
                                        <a class="nav-sub-link" href="{{ route('main.pengaturan') }}">Web
                                            Aplikasi</a>
                                    </li>
                                    {{-- <li class="nav-sub-item">
                                    <a class="nav-sub-link" href="{{ route('main.banner') }}">Banner</a>
                                </li> --}}
                                    {{-- <li class="nav-sub-item">
                                        <a class="nav-sub-link" href="{{ route('main.faq') }}">FAQ</a>
                                    </li> --}}
                                    <li class="nav-sub-item">
                                        <a class="nav-sub-link" href="{{ route('main.logs') }}">Logs</a>
                                    </li>
                                    {{-- <li class="nav-sub-item">
                                    <a class="nav-sub-link" href="{{ route('main.versi') }}">Versi</a>
                                </li> --}}
                                </ul>
                            </li>
                        @endif
                    </ul>
                    <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sidemenu -->

    <!-- Main Content-->

    @if (session()->has('message'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('message') }}",
                    type: "success"
                });
            }
        </script>
    @elseif (session()->has('error'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('error') }}",
                    type: "error"
                });
            }
        </script>
    @endif

    @yield('content')
    <!-- End Main Content-->

    <!-- Main Footer-->
    <div class="main-footer text-center">
        <div class="container">
            <div class="row row-sm">
                <div class="col-md-12">
                    <span>Copyright © 2024 - {{ date('Y') }} Design & Develop
                        <a target="_blank" href="{{ route('mainweb.index') }}"> {{ $web->nama_bisnis }}</a>.
                        All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <!--End Footer-->

</div>
<!-- End Page -->
@include('main-panel.layout.footer')
