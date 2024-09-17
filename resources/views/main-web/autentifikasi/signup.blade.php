<x-web.header-auth :title="'Vistar Indonesia | Sign Up'" />

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
    <!-- SignUp Start -->
    <section class="cover-user">
        <div class="container-fluid px-0">
            <div class="row g-0 position-relative">
                <div class="col-lg-4 cover-my-30 order-2">
                    <div class="cover-user-img d-flex align-items-center">
                        <div class="row">
                            <div class="col-12">
                                <div class="card login-page border-0" style="z-index: 1">
                                    <div class="card-body p-0">
                                        <div class="card-title text-center">
                                            <a href="{{ route('mainweb.index') }}">
                                                <img id="img-logo" src="{{ asset('storage/' . $web->logo) }}"
                                                    alt="logo">
                                            </a>
                                        </div>
                                        <form id="formRegister" action="{{ route('auth.register') }}"
                                            class="login-form mt-4" method="POST">
                                            @csrf
                                            @method('POST')
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="namaLengkap">Nama Lengkap <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5"
                                                                placeholder="Nama Lengkap..." name="namaLengkap"
                                                                required autocomplete="off"
                                                                value="{{ old('namaLengkap') }}" maxlength="50"
                                                                id="namaLengkap">
                                                        </div>
                                                        @error('namaLengkap')
                                                            <small class="text-danger"> {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="email">Email <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="user" class="fea icon-sm icons"></i>
                                                            <input type="email" class="form-control ps-5"
                                                                placeholder="Email..." name="email" required
                                                                autocomplete="off" value="{{ old('email') }}"
                                                                id="email">
                                                        </div>
                                                        @error('email')
                                                            <small class="text-danger"> {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label" id="password">
                                                            Password <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                                            <input type="password" class="form-control ps-5"
                                                                placeholder="Password..." name="password" required
                                                                id="password" autocomplete="off"
                                                                value="{{ old('password') }}">
                                                        </div>
                                                        @error('password')
                                                            <small class="text-danger"> {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="password_confirmation">
                                                            Konfirmasi Password <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                                            <input type="password" class="form-control ps-5"
                                                                placeholder="Konfirmasi Password..."
                                                                id="password_confirmation" required
                                                                name="password_confirmation" autocomplete="off"
                                                                value="{{ old('konfirmasiPassword') }}" />
                                                        </div>
                                                        @error('konfirmasiPassword')
                                                            <small class="text-danger"> {{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-12 mb-0">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-pills btn-primary">
                                                            <i class="mdi mdi-account-key"></i> Sign Up
                                                        </button>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-12 mt-4 text-center">
                                                    <h6>Daftar dengan</h6>
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="d-grid">
                                                                <a href="{{ route('auth.google') }}"
                                                                    class="btn btn-light">
                                                                    <i class="mdi mdi-google text-danger"></i>
                                                                    Google
                                                                </a>
                                                            </div>
                                                        </div><!--end col-->
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-12 text-center">
                                                    <p class="mb-0 mt-3">
                                                        <small class="text-dark me-2">
                                                            Sudah punya akun ?
                                                        </small>
                                                        <a href="{{ route('auth.signin') }}"
                                                            class="text-dark fw-bold">
                                                            Klik disini
                                                        </a>
                                                    </p>
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </form>
                                    </div>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div> <!-- end about detail -->
                </div> <!-- end col -->

                <div id="bg-paralax" class="col-lg-8 offset-lg-4 padding-less img order-1 jarallax" data-jarallax
                    data-speed="0.5"
                    style="background-image:url('{{ asset('resources/images/signin-background.jpg') }}')">
                </div>
                <!-- end col -->
            </div><!--end row-->
        </div><!--end container fluid-->
    </section><!--end section-->
    <!-- SignUp End -->

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
        $('#formRegister').on('submit', function() {
            const fullName = $('[name="namaLengkap"]').val();
            const email = $('[name="email"]').val();

            analyticsRegisterEvent({
                method: 'manual',
                userData: {
                    full_name: fullName,
                    email: email,
                },
            })
        });
    </script>
</body>

</html>
