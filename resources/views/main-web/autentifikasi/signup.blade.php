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
                                        <h4 class="card-title text-center">
                                            <a href="{{ route('mainweb.index') }}">
                                                <img id="img-logo" src="{{ asset('storage/' . $web->logo) }}"
                                                    alt="logo">
                                            </a>
                                        </h4>
                                        <form action="{{ route('auth.register') }}" class="login-form mt-4"
                                            method="POST">
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
                                                        <label class="form-label" id="password">Password <span
                                                                class="text-danger">*</span></label>
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
                                                        <label class="form-label" for="password_confirmation">Konfirmasi
                                                            Password <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                                            <input type="password" class="form-control ps-5"
                                                                placeholder="Konfirmasi Password..."
                                                                id="password_confirmation" required
                                                                name="password_confirmation" autocomplete="of"
                                                                value="{{ old('konfirmasiPassword') }}">
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
                                                                    class="btn btn-light"><i
                                                                        class="mdi mdi-google text-danger"></i>
                                                                    Google</a>
                                                            </div>
                                                        </div><!--end col-->
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-12 text-center">
                                                    <p class="mb-0 mt-3"><small class="text-dark me-2">Sudah punya
                                                            akun
                                                            ?</small> <a href="{{ route('auth.signin') }}"
                                                            class="text-dark fw-bold">Klik disini</a></p>
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
</body>

</html>
