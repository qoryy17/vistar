@php
    $showNavigation = View::getSection('theme_show_navigation') ?? true;

    $navigations = [
        [
            'title' => 'Dashboard',
            'url' => route('mitra.dashboard'),
            'icon-class' => 'ti-home',
        ],
        [
            'title' => 'Transaksi',
            'url' => route('mitra.transactions.index'),
            'icon-class' => 'ti-shopping-cart',
        ],
    ];
@endphp
<!-- Main Header-->
<div class="main-header side-header hor-header">
    <div class="main-container container">
        <div class="main-header-left">
            @if ($showNavigation)
                <a class="main-header-menu-icon" href="javascript:void(0)" id="mainSidebarToggle"><span></span></a>
            @endif
            <div class="hor-logo">
                <a class="main-logo" href="{{ route('mainweb.index') }}">
                    <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                        class="header-brand-img desktop-logo" alt="Logo {{ config('app.name') }}"
                        title="Logo {{ config('app.name') }}" style="max-width: 200px;" />
                    <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}"
                        class="header-brand-img desktop-logo-dark" alt="Logo {{ config('app.name') }}"
                        title="Logo {{ config('app.name') }}" style="max-width: 200px;" />
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
                        <!-- Profile -->
                        <div class="dropdown main-profile-menu">
                            <a class="d-flex" href="javascript:void(0)">
                                <span class="main-img-user">
                                    <img alt="avatar" title="avatar"
                                        src="{{ asset('resources/images/user-default.png') }}" />
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="header-navheading">
                                    <h6 class="main-notification-title">{{ Auth::user()->name }}</h6>
                                    <p class="main-notification-text text-bold"></p>
                                </div>
                                <a class="dropdown-item" href="{{ route('mainweb.profile') }}">
                                    <i class="fe fe-user"></i> Profile
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

@if ($showNavigation)
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
                        @foreach ($navigations as $navigation)
                            <li class="nav-item">
                                <a title={{ $navigation['title'] }} class="nav-link"
                                    href="{{ $navigation['url'] }}">
                                    <span class="shape1"></span>
                                    <span class="shape2"></span>
                                    <i class="sidemenu-icon menu-icon {{ $navigation['icon-class'] }}"></i>
                                    <span class="sidemenu-label">{{ $navigation['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sidemenu -->
@endif