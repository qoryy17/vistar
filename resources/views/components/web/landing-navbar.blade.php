@php
    $navigationUrls = [
        [
            'title' => 'Cek Sertifikat',
            'url' => route('mainweb.certificate-sertikom'),
        ],
        [
            'title' => 'Tentang',
            'url' => route('mainweb.about-us'),
        ],
        [
            'title' => 'Kontak',
            'url' => route('mainweb.contact-us'),
        ],
    ];

    $navigationSubUrls = [
        [
            'title' => 'Pelatihan',
            'url' => route('mainweb.product-sertikom', ['category' => 'pelatihan']),
        ],
        [
            'title' => 'Seminar',
            'url' => route('mainweb.product-sertikom', ['category' => 'seminar']),
        ],
        [
            'title' => 'Workshop',
            'url' => route('mainweb.product-sertikom', ['category' => 'workshop']),
        ],
        [
            'title' => 'Tryout Simulasi CAT',
            'url' => route('mainweb.product'),
        ],
    ];

    $noNavigation = 1;
@endphp
<!-- Navbar Start -->
<header id="topnav" class="defaultscroll sticky d-print-none">
    <div class="container">
        <!-- Logo container-->
        <a title="Beranda {{ config('app.name') }}" class="logo" href="{{ url('/') }}">
            <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="40" class="logo-light-mode"
                alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo" loading="eager" />
            <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="40" class="logo-dark-mode"
                alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo" loading="eager" />
        </a>
        <!-- Logo End -->

        <!-- End Logo container-->
        <div class="menu-extras">
            <div class="menu-item">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </div>
        </div>

        <!--Login button Start-->
        <ul class="buy-button list-inline mb-0">
            @if (Auth::check())
                <li class="list-inline-item ps-1 mb-0">
                    <form id="formLogout" action="{{ route('auth.signout-proses') }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-primary btn-pills" title="Keluar">
                            Keluar
                        </button>
                    </form>
                </li>
            @else
                <li class="list-inline-item ps-1 mb-0">
                    <a href="{{ route('auth.signin') }}" class="btn btn-primary btn-pills">
                        <span>
                            Masuk
                        </span>
                    </a>
                    <meta itemprop="position" content="{{ $noNavigation++ }}" />
                </li>
            @endif
        </ul>
        <!--Login button End-->

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu nav-right">
                <li>
                    <a title="Home" href="{{ route('mainweb.index') }}" class="sub-menu-item">
                        <span>
                            Home
                        </span>
                    </a>
                    <meta itemprop="position" content="{{ $noNavigation++ }}" />
                </li>
                <!-- Menu Produk -->
                <li class="has-submenu parent-parent-menu-item">
                    <a href="javascript:void(0)">
                        Produk
                    </a>
                    <span class="menu-arrow"></span>
                    <ul class="submenu">
                        @foreach ($navigationSubUrls as $navigationSub)
                            <li>
                                <a title="{{ $navigationSub['title'] }}" href="{{ $navigationSub['url'] }}"
                                    class="sub-menu-item">
                                    <span>
                                        {{ $navigationSub['title'] }}
                                    </span>
                                </a>
                                <meta itemprop="position" content="{{ $noNavigation++ }}" />
                            </li>
                        @endforeach
                    </ul>
                </li>
                @foreach ($navigationUrls as $navigation)
                    <li>
                        <a title="{{ $navigation['title'] }}" href="{{ $navigation['url'] }}" class="sub-menu-item">
                            <span>
                                {{ $navigation['title'] }}
                            </span>
                        </a>
                        <meta itemprop="position" content="{{ $noNavigation++ }}" />
                    </li>
                @endforeach

                @if (Auth::check())
                    <li class="has-submenu parent-parent-menu-item">
                        <a href="javascript:void(0)">
                            Dashboard
                        </a>
                        <span class="menu-arrow"></span>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('mainweb.profile') }}" class="sub-menu-item">
                                    <span>
                                        Profil
                                    </span>
                                </a>
                                <meta itemprop="position" content="{{ $noNavigation++ }}" />
                            </li>
                            <li>
                                <a href="{{ route('user.dashboard') }}" class="sub-menu-item">
                                    <span>
                                        Dashboard
                                    </span>
                                </a>
                                <meta itemprop="position" content="{{ $noNavigation++ }}" />
                            </li>
                            @if (Auth::user()->role == 'Customer')
                                <li class="has-submenu parent-menu-item"><a href="javascript:void(0)"> Keranjang
                                    </a><span class="submenu-arrow"></span>
                                    <ul class="submenu">
                                        <li>
                                            <a href="{{ route('mainweb.cart-sertikom', ['category' => 'pelatihan']) }}"
                                                class="sub-menu-item">
                                                <span>
                                                    Keranjang Pelatihan
                                                </span>
                                            </a>
                                            <meta itemprop="position" content="{{ $noNavigation++ }}" />
                                        </li>
                                        <li>
                                            <a href="{{ route('mainweb.cart-sertikom', ['category' => 'seminar']) }}"
                                                class="sub-menu-item">
                                                <span>
                                                    Keranjang Seminar
                                                </span>
                                            </a>
                                            <meta itemprop="position" content="{{ $noNavigation++ }}" />
                                        </li>
                                        <li>
                                            <a href="{{ route('mainweb.cart-sertikom', ['category' => 'workshop']) }}"
                                                class="sub-menu-item">
                                                <span>
                                                    Keranjang Workshop
                                                </span>
                                            </a>
                                            <meta itemprop="position" content="{{ $noNavigation++ }}" />
                                        </li>
                                        <li>
                                            <a href="{{ route('mainweb.keranjang') }}" class="sub-menu-item">
                                                <span>
                                                    Keranjang Tryout
                                                </span>
                                            </a>
                                            <meta itemprop="position" content="{{ $noNavigation++ }}" />
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="{{ route('mainweb.free-product') }}" class="sub-menu-item">
                                        <span>
                                            Tryout Gratis
                                        </span>
                                    </a>
                                    <meta itemprop="position" content="{{ $noNavigation++ }}" />
                                </li>
                            @endif
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route('auth.signup') }}" class="sub-menu-item">
                            <span>
                                Daftar
                            </span>
                        </a>
                        <meta itemprop="position" content="{{ $noNavigation++ }}" />
                    </li>
                @endif
            </ul><!--end navigation menu-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->
