@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();
@endphp
<!-- Footer Start -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-py-60">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                            <a href="#" class="logo-footer">
                                <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="50"
                                    alt="">
                            </a>
                            <p class="mt-4">{{ $web->nama_bisnis }} {{ $web->tagline }}, Ujian Tryout untuk CPNS, PPPK
                                dan
                                Kedinasan Terpercaya Seluruh Indonesia.</p>
                            <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-3">
                                <li class="list-inline-item mb-0"><a href="{{ $web->instagram }}" target="_blank"
                                        class="rounded"><i class="uil uil-instagram align-middle"
                                            title="Instagram {{ $web->nama_bisnis }}"></i></a>
                                </li>
                                <li class="list-inline-item mb-0"><a href="mailto:{{ $web->email }}"
                                        class="rounded"><i class="uil uil-envelope align-middle"
                                            title="Email {{ $web->nama_bisnis }}"></i></a>
                                </li>
                            </ul><!--end icon-->
                            <p class="mt-3">
                                {{ $web->perusahaan }}
                            </p>
                        </div><!--end col-->

                        <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">Link Terkait</h5>
                            <ul class="list-unstyled footer-list mt-4">
                                @if (!Auth::check())
                                    <li><a href="{{ route('auth.signin') }}" class="text-foot"><i
                                                class="uil uil-angle-right-b me-1"></i> Sign In</a></li>
                                @endif
                                <li><a href="{{ route('mainweb.produk-berbayar') }}" class="text-foot"><i
                                            class="uil uil-angle-right-b me-1"></i> Produk</a></li>
                                <li><a href="{{ route('mainweb.kebijakan-privasi') }}" class="text-foot"><i
                                            class="uil uil-angle-right-b me-1"></i> Kebijakan & Privasi</a></li>
                                <li><a href="{{ route('mainweb.kontak') }}" class="text-foot"><i
                                            class="uil uil-angle-right-b me-1"></i> Kontak</a></li>
                            </ul>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">Alamat</h5>
                            <p class="mt-4">{{ $web->alamat ? $web->alamat : '' }}</p>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">Pembayaran</h5>

                            <img height="20" src="{{ asset('resources/midtrans.png') }}" alt="midtrans">
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div class="footer-py-30 footer-bar">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="text-sm-start">
                        <p class="mb-0">Copyright ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Design & Develop By <a href="https://vistar.id/" target="_blank"
                                class="text-reset">{{ $web->nama_bisnis }}</a>.
                        </p>
                    </div>
                </div><!--end col-->

            </div><!--end row-->
        </div><!--end container-->
    </div>
</footer><!--end footer-->
<!-- Footer End -->
