@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();

    $logo = asset(is_file('storage/' . $web->logo) ? 'storage/' . $web->logo : 'resources/images/logo.png');
    $logoDark = asset(is_file('storage/' . $web->logo) ? 'storage/' . $web->logo : 'resources/images/logo-white.png');

    $logoMobile = $logo;
    $logoMobileDark = $logoDark;
@endphp
@include('customer-panel.layout.header')
<!-- Page -->
<div class="page">
    <!-- Main Header-->
    <div class="main-header side-header hor-header">
        <div class="main-container container">
            <div class="main-header-left">
                @if ($showSideMenu ?? true)
                    <a class="main-header-menu-icon" href="javascript:void(0)" id="mainSidebarToggle"><span></span></a>
                @endif
                <div class="hor-logo">
                    <a class="main-logo" href="{{ route('mainweb.index') }}">
                        <img src="{{ $logo }}" class="header-brand-img desktop-logo"
                            style="max-width: 200px; max-height: 50px;" alt="{{ config('app.name') }} Logo"
                            title="{{ config('app.name') }} Logo" loading="eager" />
                        <img src="{{ $logoDark }}" class="header-brand-img desktop-logo-dark"
                            style="max-width: 200px; max-height: 50px;" alt="{{ config('app.name') }} Logo Dark"
                            title="{{ config('app.name') }} Logo Dark" loading="eager" />
                    </a>
                </div>
                <div class="mt-2 p-3">
                    <p class="title-web text-primary fs-6 fw-bold mb-0">
                        {{ $web->tagline }}
                    </p>
                    <span class="address-web">{{ $web->alamat }}</span>
                </div>
            </div>
            <div class="main-header-center">
                <div class="responsive-logo">
                    <a href="{{ route('mainweb.index') }}">
                        <img src="{{ $logoMobile }}" class="mobile-logo" style="max-width: 120px; max-height: 40px;"
                            alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo"
                            loading="eager" />
                    </a>
                    <a href="{{ route('mainweb.index') }}">
                        <img src="{{ $logoMobileDark }}" class="mobile-logo-dark"
                            style="max-width: 120px; max-height: 40px;" alt="{{ config('app.name') }} Logo Dark"
                            title="{{ config('app.name') }} Logo Dark" loading="eager" />
                    </a>
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
                            <!-- Profile -->
                            <div class="dropdown main-profile-menu">
                                <a class="d-flex" href="javascript:void(0)">
                                    <span class="main-img-user">
                                        <img src="{{ $customer->foto ? asset('storage/user/' . $customer->foto) : asset('resources/images/user-default.png') }}"
                                            alt="Avatar Pengguna" title="Avatar Pengguna" loading="eager" />
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="header-navheading">
                                        <p class="main-notification-title">{{ $customer->nama_lengkap }}</p>
                                        <p class="main-notification-text text-bold">{{ $customer->pendidikan }} -
                                            {{ $customer->jurusan }}</p>
                                    </div>
                                    <a class="dropdown-item" href="{{ route('mainweb.profile') }}">
                                        <i class="fe fe-user"></i> Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('site.pembelian') }}">
                                        <i class="fe fe-shopping-cart"></i> Pembelian
                                    </a>
                                    <form id="formLogout" action="{{ route('auth.signout-proses') }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button class="dropdown-item" type="submit">
                                            <i class="fe fe-power"></i> Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Header-->

    @if ($showSideMenu ?? true)
        <!-- Sidemenu -->
        <div class="sticky">
            <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
                <div class="main-sidebar-header main-container-1 active">
                    <div class="main-sidebar-body main-body-1">
                        <div class="slide-left disabled" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                        <ul class="menu-nav nav">
                            <li class="nav-header"><span class="nav-label">Dashboard</span></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('site.main') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-home sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Beranda</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('site.pembelian') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-shopping-cart sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Pembelian</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('site.tryout-berbayar') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-desktop sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Tryout Berbayar</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('site.tryout-gratis') }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="ti-desktop sidemenu-icon menu-icon "></i>
                                    <span class="sidemenu-label">Tryout Gratis</span>
                                </a>
                            </li>
                        </ul>
                        <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Sidemenu -->
    @endif

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
                        <a target="_blank" href="{{ route('mainweb.index') }}">{{ $web->nama_bisnis }}</a>.
                        All rights reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <!--End Footer-->

</div>
<!-- End Page -->
@include('customer-panel.layout.footer')
