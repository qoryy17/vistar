<!-- Navbar Start -->
<header id="topnav" class="defaultscroll sticky d-print-none">
    <div class="container">
        <!-- Logo container-->
        <a title="Beranda {{ config('app.name') }}" class="logo" href="{{ url('/') }}">
            <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="40" class="logo-light-mode"
                alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo">
            <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="40" class="logo-dark-mode"
                alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo">
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
                        Masuk
                    </a>
                </li>
            @endif
        </ul>
        <!--Login button End-->

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu nav-right">
                <li><a href="{{ route('mainweb.index') }}" class="sub-menu-item">Home</a></li>
                <li><a href="{{ route('mainweb.product') }}" class="sub-menu-item">Produk</a></li>
                <li><a href="{{ route('mainweb.tentang') }}" class="sub-menu-item">Tentang</a></li>
                <li><a href="{{ route('mainweb.kontak') }}" class="sub-menu-item">Kontak</a></li>

                @if (Auth::check())
                    <li class="has-submenu parent-parent-menu-item">
                        <a href="javascript:void(0)">Dashboard</a><span class="menu-arrow"></span>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('user.dashboard') }}" class="sub-menu-item">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('mainweb.profile') }}" class="sub-menu-item">
                                    Profil
                                </a>
                            </li>
                            @if (Auth::user()->role == 'Customer')
                                <li>
                                    <a href="{{ route('mainweb.keranjang') }}" class="sub-menu-item">
                                        Keranjang Pesanan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mainweb.free-product') }}" class="sub-menu-item">
                                        Tryout Gratis
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @else
                    <li><a href="{{ route('auth.signup') }}" class="sub-menu-item">Daftar</a></li>
                @endif
            </ul><!--end navigation menu-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->
