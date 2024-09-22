<x-web.header-auth :title="'Lupa Password - Vistar Indonesia'" :keywords="'Forget Password, Forget Password Vistar Indonesia, Lupa Password, Lupa Password Vistar Indonesia'" />

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
    @endif
    <div class="back-to-home">
        <button title="Kembali" class="back-button btn btn-icon btn-primary"><i data-feather="arrow-left"
                class="icons"></i></button>
    </div>

    <!-- Hero Start -->
    <section class="bg-home bg-circle-gradiant">
        <div class="bg-overlay bg-overlay-white"></div>
        <div class="d-flex align-items-center overflow-y-auto  vh-100 vw-100">
            <div class="container my-auto">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8">
                        <div class="card shadow rounded border-0">
                            <div class="card-body">
                                <h1 class="fs-4 card-title text-center">Reset Password</h1>

                                <form class="login-form mt-4" action="{{ route('auth.send-link-email') }}"
                                    method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p class="text-muted">Masukan email anda yang terdaftar, untuk mendapatkan
                                                link
                                                reset password melalui email</p>
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email <span
                                                        class="text-danger">*</span></label>
                                                <div class="form-icon position-relative">
                                                    <i data-feather="mail" class="fea icon-sm icons"></i>
                                                    <input type="email" id="email" class="form-control ps-5"
                                                        placeholder="Masukan email anda..." name="email" required>
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                        <div class="col-lg-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-pills btn-primary">Kirim</button>
                                            </div>
                                        </div><!--end col-->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div> <!--end container-->
        </div>
    </section><!--end section-->
    <!-- Hero End -->

    <x-web.footer-auth />
</body>

</html>
