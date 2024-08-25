<x-web.header-auth :title="'Vi Star Indonesia | Reset Password'" />

<body>
    @if (session()->has('error'))
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
    <div class="back-to-home">
        <a href="" class="back-button btn btn-icon btn-primary"><i data-feather="arrow-left" class="icons"></i></a>
    </div>

    <!-- Hero Start -->
    <section class="bg-home bg-circle-gradiant d-flex align-items-center">
        <div class="bg-overlay bg-overlay-white"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card shadow rounded border-0">
                        <div class="card-body">
                            <h4 class="card-title text-center">Konfirmasi Password Baru</h4>

                            <form class="login-form mt-4" action="{{ route('auth.simpanPasswordReset') }}"
                                method="POST">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p class="text-muted">Silahkan isi password baru anda</p>
                                        <div class="mb-3" hidden>
                                            <label class="form-label" for="token">Token <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="code" class="fea icon-sm icons"></i>
                                                <input type="token" class="form-control ps-5"
                                                    placeholder="Masukan token anda..." name="token" required readonly
                                                    value="{{ $token }}">
                                            </div>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="mail" class="fea icon-sm icons"></i>
                                                <input type="email" class="form-control ps-5"
                                                    placeholder="Masukan email anda..." name="email" required readonly
                                                    value="{{ $email ?? old('email') }}">
                                            </div>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password Baru <span
                                                    class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="key" class="fea icon-sm icons"></i>
                                                <input type="password" id="password" class="form-control ps-5"
                                                    placeholder="Masukan password baru..." name="password" required>
                                            </div>
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password_confirmation">Konfirmasi Password
                                                <span class="text-danger">*</span></label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="key" class="fea icon-sm icons"></i>
                                                <input type="password" id="password_confirmation"
                                                    class="form-control ps-5"
                                                    placeholder="Masukan konfirmasi password..."
                                                    name="password_confirmation" required>
                                            </div>
                                            @error('password_confirmation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-lg-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-pills btn-primary">Simpan</button>
                                        </div>
                                    </div><!--end col-->
                                    <div class="mx-auto">
                                        <p class="mb-0 mt-3"><small class="text-dark me-2">Sudah Punya Akun ?
                                                Klik</small>
                                            <a href="{{ route('auth.signin') }}" class="text-dark fw-bold">Sign
                                                in</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div> <!--end container-->
    </section><!--end section-->
    <!-- Hero End -->

    <x-web.footer-auth />
</body>

</html>
