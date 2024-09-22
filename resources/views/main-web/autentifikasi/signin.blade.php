<x-web.header-auth :title="'Masuk - Vistar Indonesia'" :keywords="'Login, Login Vistar Indonesia, Masuk, Masuk Vistar Indonesia'" />

<body>
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
    @elseif (session()->has('info'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('info') }}",
                    type: "info"
                });
            }
        </script>
    @elseif (session()->has('loginMessage'))
        <script>
            window.onload = function() {
                swal({
                    title: "Notifikasi",
                    text: " {{ session('loginMessage') }}",
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
    <!-- SignIn Start -->
    <section class="cover-user">
        <div class="container-fluid px-0">
            <div class="row g-0 position-relative">
                <div class="col-lg-4 cover-my-30 order-2">
                    <div class="cover-user-img d-flex align-items-center overflow-y-auto">
                        <div class="my-auto">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card login-page border-0" style="z-index: 1;">
                                        <div class="card-body p-0">
                                            <div class="card-title text-center">
                                                <a href="{{ route('mainweb.index') }}">
                                                    <img id="img-logo" src="{{ asset('storage/' . $web->logo) }}"
                                                        alt="{{ config('app.name') }} Logo"
                                                        title="{{ config('app.name') }} Logo" />
                                                </a>
                                                {{--  SEO Purpose  --}}
                                                <h1 class="fs-4 hide my-3">Masuk</h1>
                                            </div>
                                            <form id="formLogin" action="{{ route('auth.signin-proses') }}"
                                                class="login-form mt-4" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="email">Email <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="user" class="fea icon-sm icons"></i>
                                                                <input type="email" class="form-control ps-5"
                                                                    placeholder="Email..." name="email" required
                                                                    autocomplete="off" id="email"
                                                                    value="{{ old('email') }}">
                                                            </div>
                                                            @error('email')
                                                                <small class="text-danger">* {{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div><!--end col-->

                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="password">Password <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="key" class="fea icon-sm icons"></i>
                                                                <input type="password" class="form-control ps-5"
                                                                    placeholder="Password..." name="password" required
                                                                    autocomplete="off" id="password"
                                                                    value="{{ old('password') }}" />
                                                            </div>
                                                            @error('password')
                                                                <small class="text-danger">* {{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div><!--end col-->

                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between">
                                                            <p class="forgot-pass mb-3">
                                                                <a title="Lupa Password ?"
                                                                    href="{{ route('auth.reset-password') }}"
                                                                    class="text-dark fw-bold">Lupa Password ?</a>
                                                            </p>
                                                        </div>
                                                    </div><!--end col-->

                                                    <div class="col-lg-12 mb-0">
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-pills btn-primary">
                                                                <i class="mdi mdi-account-key"></i> Masuk
                                                            </button>
                                                        </div>
                                                    </div><!--end col-->

                                                    <div class="col-lg-12 mt-4 text-center">
                                                        <p class="fs-6 fw-bold m-0">Masuk dengan</p>
                                                        <div class="row">
                                                            <div class="col-12 mt-3">
                                                                <div class="d-grid">
                                                                    <a title="Masuk dengan Google"
                                                                        href="{{ route('auth.google') }}"
                                                                        class="btn btn-light">
                                                                        <i class="mdi mdi-google text-danger"></i>
                                                                        Google
                                                                    </a>
                                                                </div>
                                                            </div><!--end col-->
                                                        </div>
                                                    </div><!--end col-->

                                                    <div class="col-12 text-center">
                                                        <p class="mb-0 mt-3"><small class="text-dark me-2">Belum punya
                                                                akun
                                                                ?</small> <a href="{{ route('auth.signup') }}"
                                                                class="text-dark fw-bold">Klik disini</a></p>
                                                    </div><!--end col-->
                                                </div><!--end row-->
                                            </form>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div> <!-- end margin auto -->
                    </div> <!-- end about detail -->
                </div> <!-- end col -->

                <div id="bg-paralax" class="col-lg-8 offset-lg-4 padding-less img order-1 jarallax" data-jarallax
                    data-speed="0.5"
                    style="background-image: url('{{ asset('resources/images/signin-background.jpg') }}');">
                </div>
                <!-- end col -->
            </div><!--end row-->
        </div><!--end container fluid-->
    </section><!--end section-->
    <!-- SignIn End -->

    <x-web.footer-auth />

    {{--  Analytics  --}}
    {{--  Google tag (gtag.js)  --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytic.google.id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        $(document).ready(function() {
            gtag('js', new Date());
            gtag('config', "{{ config('services.analytic.google.id') }}");
        });
    </script>

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');

        $(document).ready(function() {
            fbq('init', "{{ config('services.analytic.facebook.id') }}");
            fbq('track', 'PageView');
        });
    </script>
    <noscript><img alt="" height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ config('services.analytic.facebook.id') }}&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <script src="{{ asset('resources/web/dist/assets/js/analytics.js') }}"></script>
    <script>
        $('#formLogin').on('submit', function() {
            const email = $('[name="email"]').val();

            analyticsLoginEvent({
                method: 'manual',
                userData: {
                    email: email,
                },
            })
        });
    </script>
</body>

</html>
