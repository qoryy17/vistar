@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();
@endphp
<!-- Footer Start -->
<footer class="footer d-print-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-py-60">
                    <div class="row">
                        <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                            <a href="#" class="logo-footer">
                                <img src="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" height="50"
                                    alt="{{ config('app.name') }} Logo" title="{{ config('app.name') }} Logo"
                                    loading="lazy" />
                            </a>
                            <p class="mt-4">
                                {{ $web->nama_bisnis }} {{ $web->tagline }}, Pusat Kegiatan Akademik
                                Bidang ICT dan Science Terbaik #1 di Indonesia
                            </p>
                            <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-3">
                                @if ($web->facebook && $web->facebook !== '')
                                    <li class="list-inline-item mb-0">
                                        <a title="Kunjungi Facebook {{ $web->nama_bisnis }}" href="{{ $web->facebook }}"
                                            target="_blank" class="rounded">
                                            {{--  SEO Purpose  --}}
                                            <span class="hide">Facebook</span>
                                            <i class="uil uil-facebook align-middle"></i>
                                        </a>
                                    </li>
                                @endif
                                @if ($web->instagram && $web->instagram !== '')
                                    <li class="list-inline-item mb-0">
                                        <a title="Kunjungi Instagram {{ $web->nama_bisnis }}"
                                            href="{{ $web->instagram }}" target="_blank" class="rounded">
                                            {{--  SEO Purpose  --}}
                                            <span class="hide">Instagram</span>
                                            <i class="uil uil-instagram align-middle"></i>
                                        </a>
                                    </li>
                                @endif
                                @if ($web->email && $web->email !== '')
                                    <li class="list-inline-item mb-0">
                                        <a title="Kirim Email ke {{ $web->nama_bisnis }}"
                                            href="mailto:{{ $web->email }}" class="rounded">
                                            {{--  SEO Purpose  --}}
                                            <span class="hide">E-Mail</span>
                                            <i class="uil uil-envelope align-middle"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul><!--end icon-->
                            <p class="mt-3">
                                {{ $web->perusahaan }}
                            </p>
                        </div><!--end col-->

                        <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <p class="footer-head fs-5">Link Terkait</p>
                            <ul class="list-unstyled footer-list mt-4">
                                @if (!Auth::check())
                                    <li>
                                        <a href="{{ route('auth.signin') }}" class="text-foot">
                                            <i class="uil uil-angle-right-b me-1"></i> Masuk
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('mainweb.product') }}" class="text-foot">
                                        <i class="uil uil-angle-right-b me-1"></i> Produk
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mainweb.contact-us') }}" class="text-foot">
                                        <i class="uil uil-angle-right-b me-1"></i> Kontak</a>
                                </li>
                                <li>
                                    <a href="{{ route('mainweb.privacy-policy') }}" class="text-foot">
                                        <i class="uil uil-angle-right-b me-1"></i> Kebijakan & Privasi
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mainweb.term-of-service') }}" class="text-foot">
                                        <i class="uil uil-angle-right-b me-1"></i> Syarat & Ketentuan
                                    </a>
                                </li>
                            </ul>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <p class="footer-head fs-5">Alamat</p>
                            <p class="mt-4">{{ $web->alamat ? $web->alamat : '' }}</p>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <p class="footer-head fs-5">Pembayaran</p>
                            <div class="d-flex gap-4 flex-wrap mt-4 justify-content-around">
                                <img height="20" src="{{ asset('resources/payment-methods/Bank-CIMB-Niaga.png') }}"
                                    alt="Logo CIMB Niaga" title="Logo CIMB Niaga" loading="lazy" />
                                <img height="20" src="{{ asset('resources/payment-methods/Bank-BRI.png') }}"
                                    alt="Logo BRI" title="Logo BRI" loading="lazy" />
                                <img height="20" src="{{ asset('resources/payment-methods/Bank-BNI.png') }}"
                                    alt="Logo BNI" title="Logo BNI" loading="lazy" />
                                <img height="20" src="{{ asset('resources/payment-methods/Bank-Mandiri.png') }}"
                                    alt="Logo Mandiri" title="Logo Mandiri" loading="lazy" />
                                <img height="20" src="{{ asset('resources/payment-methods/Gopay-white.png') }}"
                                    alt="Logo Gopay" title="Logo Gopay" loading="lazy" />
                                <img height="20"
                                    src="{{ asset('resources/payment-methods/Bank-Permata-white.png') }}"
                                    alt="Logo Permata" title="Logo Permata" loading="lazy" />
                            </div>
                            <div class="d-flex gap-2 mt-4 justify-content-center align-items-center">
                                <span>Powered By</span>
                                <img height="20" src="{{ asset('resources/payment-methods/midtrans.png') }}"
                                    alt="Logo Midtrans" title="Logo Midtrans" loading="lazy" />
                            </div>
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
                        <p class="mb-0">
                            Copyright © {{ date('Y') }} Design & Develop By <a
                                title="Beranda {{ config('app.name') }}" href="{{ url('/') }}"
                                class="text-reset">{{ $web->nama_bisnis }}</a>.
                        </p>
                    </div>
                </div><!--end col-->

            </div><!--end row-->
        </div><!--end container-->
    </div>
</footer><!--end footer-->
<!-- Footer End -->
