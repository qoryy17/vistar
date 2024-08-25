@include('customer-panel.layout.header')
<!-- Page -->
<div class="page">
    @php
        use App\Models\PengaturanWeb;
        $web = PengaturanWeb::all()->first();
    @endphp
    <!-- Main Header-->
    <div class="main-header side-header hor-header">
        <div class="main-container container">
            <div class="main-header-left">
                <a class="main-header-menu-icon" href="javascript:void(0)" id="mainSidebarToggle"><span></span></a>
                <div class="hor-logo">
                    <a class="main-logo" href="{{ route('site.main') }}">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="header-brand-img desktop-logo"
                            alt="logo" style="max-width: 200px;">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="header-brand-img desktop-logo-dark"
                            alt="logo" style="max-width: 200px;">
                    </a>
                </div>
                <div class="mt-2 p-3">
                    <h5 class="title-web text-primary">
                        {{ $web->tagline }}
                    </h5>
                    <span class="address-web">{{ $web->alamat }}</span>
                </div>
            </div>
            <div class="main-header-center">
                <div class="responsive-logo">
                    <a href="{{ route('site.main') }}"><img src="{{ asset('storage/' . $web->logo) }}"
                            class="mobile-logo" alt="logo" style="max-width: 120px;"></a>
                    <a href="{{ route('site.main') }}"><img src="{{ url('resources/Vistar-white.png') }}"
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
                            <!-- Profile -->
                            <div class="dropdown main-profile-menu">
                                <a class="d-flex" href="javascript:void(0)">
                                    <span class="main-img-user"><img alt="avatar"
                                            src="{{ asset('storage/user/' . $customer->foto) }}"></span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="header-navheading">
                                        <h6 class="main-notification-title">{{ $customer->nama_lengkap }}</h6>
                                        <p class="main-notification-text text-bold">{{ $customer->pendidikan }} -
                                            {{ $customer->jurusan }}</p>
                                    </div>
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

    <!-- Sidemenu -->
    <div class="sticky">
        <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
            <div class="main-sidebar-header main-container-1 active">
                <div class="sidemenu-logo">
                    <a class="main-logo" href="{{ route('site.main') }}">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="header-brand-img desktop-logo"
                            alt="logo">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="header-brand-img icon-logo"
                            alt="logo">
                        <img src="{{ asset('storage/' . $web->logo) }}"
                            class="header-brand-img desktop-logo theme-logo" alt="logo">
                        <img src="{{ asset('storage/' . $web->logo) }}" class="header-brand-img icon-logo theme-logo"
                            alt="logo">
                    </a>
                </div>
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
                            <a class="nav-link" href="{{ route('mainweb.index') }}">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-world sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Web Utama</span>
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
                        {{-- <li class="nav-item">
                            <a class="nav-link with-sub" href="javascript:void(0)">
                                <span class="shape1"></span>
                                <span class="shape2"></span>
                                <i class="ti-calendar sidemenu-icon menu-icon "></i>
                                <span class="sidemenu-label">Event</span>
                                <i class="angle fe fe-chevron-right"></i>
                            </a>
                            <ul class="nav-sub">
                                <li class="side-menu-label1"><a href="javascript:void(0)">Submenu</a></li>
                                <li class="nav-sub-item">
                                    <a class="nav-sub-link" href="{{ route('site.event-tryout') }}">Tryout</a>
                                </li>
                            </ul>
                        </li> --}}
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
                        <a target="_blank" href="{{ route('mainweb.index') }}">Vi Star</a>.
                        All rights reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <!--End Footer-->

</div>
<!-- End Page -->
@include('customer-panel.layout.footer')
