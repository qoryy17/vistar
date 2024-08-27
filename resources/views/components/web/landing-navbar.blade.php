<!-- Navbar Start -->
<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{ url('/') }}">
            <img src="{{ asset('public/' . $web->logo ? $web->logo : '') }}" height="40" class="logo-light-mode"
                alt="">
            <img src="{{ asset('public/' . $web->logo ? $web->logo : '') }}" height="40" class="logo-dark-mode"
                alt="">
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
                        <a href="#" class="btn btn-primary btn-pills" onclick="submitForm()">
                            Keluar
                        </a>
                    </form>
                    <script>
                        function submitForm() {
                            document.getElementById('formLogout').submit();
                        }
                    </script>

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
                <li><a href="{{ route('mainweb.produk-berbayar') }}" class="sub-menu-item">Produk</a></li>
                <li><a href="#" class="sub-menu-item">Bantuan</a></li>
                <li><a href="#" class="sub-menu-item">Tentang</a></li>
                <li><a href="#" class="sub-menu-item">Kontak</a></li>
                @if (Auth::check())
                    @if (Auth::user()->role == 'Customer')
                        <li class="has-submenu parent-parent-menu-item">
                            <a href="javascript:void(0)">Akun</a><span class="menu-arrow"></span>
                            <ul class="submenu">
                                <li><a href="{{ route('mainweb.profil-saya') }}" class="sub-menu-item">
                                        Profil
                                    </a>
                                </li>
                                <li><a href="{{ route('site.main') }}" class="sub-menu-item">Go To Dashboard</a>
                                </li>
                                <li><a href="{{ route('mainweb.keranjang') }}" class="sub-menu-item">
                                        Keranjang Pesanan
                                    </a>
                                </li>
                                <li><a href="{{ route('mainweb.produk-gratis') }}" class="sub-menu-item">
                                        Tryout Gratis
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="has-submenu parent-parent-menu-item">
                            <a href="javascript:void(0)">Profil</a><span class="menu-arrow"></span>
                            <ul class="submenu">
                                <li><a href="{{ route('main.beranda') }}" class="sub-menu-item">Go To Dashboard</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endif

            </ul><!--end navigation menu-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->
