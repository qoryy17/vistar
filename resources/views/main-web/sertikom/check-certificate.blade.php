@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Hero Start -->
    <section class="section" style="margin-top: 50px;">
        <div class="container-fluid">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h4 class="title"> Cek Sertifikat Kamu Disini !</h4>
                        <p>Jika kamu telah melakukan pelatihan, seminar, ataupun workshop kamu dapat memverifikasi
                            sertifikat kamu di halaman ini ya.</p>

                        <div class="subcribe-form mt-4 pt-2">
                            <form action="{{ route('mainweb.certificate-sertikom') }}" method="GET">
                                <div class="mb-0">
                                    <input type="text" id="kode_peserta" name="kode_peserta"
                                        class="border shadow rounded-pill" placeholder="Masukan Kode Peserta Kamu">
                                    <button type="submit" class="btn btn-pills btn-primary">Verifikasi</button>
                                </div>
                                <br>
                                <small>Contoh : {{ url()->current() . '?kode_peserta=RtvE' }} <br> Maka kode
                                    peserta kamu <strong>RtvE</strong>
                                </small>
                            </form>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div> <!--end container-->

        <div class="container mt-5">
            @if ($result == 'Found')
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <h5>Informasi Sertifikat Kamu</h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nomor Sertifikat
                            </td>
                            <td>: {{ $certificate['certificateNumber'] }} </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                Kode Peserta
                            </td>
                            <td>: {{ $certificate['participant']->kode_peserta }} </td>
                        </tr>
                        <tr>
                            <td>
                                Nama Peserta
                            </td>
                            <td>: {{ $certificate['participant']->nama }}</td>
                        </tr>
                        <tr>
                            <td>
                                Produk
                            </td>
                            <td>: {{ $certificate['product']->produk }}</td>
                        </tr>
                        <tr>
                            <td>
                                Harga
                            </td>
                            <td>: Rp. {{ Number::Format($certificate['product']->harga, 0) }}</td>
                        </tr>
                        <tr>
                            <td>
                                Tanggal Beli
                            </td>
                            <td>: {{ $certificate['order']->created_at }}</td>
                        </tr>

                    </table>
                </div>
            @elseif ($result == 'Not Found')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert bg-soft-danger fw-medium" role="alert"> <i
                                class="uil uil-info-circle fs-5 align-middle me-1"></i>
                            Maaf anda tidak terdaftar sebagai peserta dalam kegiatan pelatihan, seminar ataupun workshop !.
                            Pastikan kode peserta yang ada masukan benar!
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mt-5">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="d-flex align-items-center features feature-primary feature-clean shadow rounded p-4">
                        <div class="flex-1 content ms-4">
                            <h5 class="mb-1"><a href="javascript:void(0)" class="text-dark">Pelatihan</a></h5>
                            <p class="text-muted mb-0">Tunggu apalagi, daftarkan diri kamu untuk mengikuti pelatihan di
                                {{ $web->nama_bisnis }}</p>
                            <div class="mt-2">
                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'pelatihan']) }}"
                                    class="btn btn-sm btn-soft">Lihat Pelatihan</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6 col-md-6 col-12">
                    <div class="d-flex align-items-center features feature-primary feature-clean shadow rounded p-4">
                        <div class="flex-1 content ms-4">
                            <h5 class="mb-1"><a href="javascript:void(0)" class="text-dark">Seminar</a></h5>
                            <p class="text-muted mb-0">Tunggu apalagi, daftarkan diri kamu untuk mengikuti seminar di
                                {{ $web->nama_bisnis }}</p>
                            <div class="mt-2">
                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'seminar']) }}"
                                    class="btn btn-sm btn-soft">Lihat Seminar</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6 col-md-6 col-12 mt-4">
                    <div class="d-flex align-items-center features feature-primary feature-clean shadow rounded p-4">
                        <div class="flex-1 content ms-4">
                            <h5 class="mb-1"><a href="javascript:void(0)" class="text-dark">Workshop</a></h5>
                            <p class="text-muted mb-0">Tunggu apalagi, daftarkan diri kamu untuk mengikuti workshop di
                                {{ $web->nama_bisnis }}</p>
                            <div class="mt-2">
                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}"
                                    class="btn btn-sm btn-soft">Lihat Workshop</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-lg-6 col-md-6 col-12 mt-4">
                    <div class="d-flex align-items-center features feature-primary feature-clean shadow rounded p-4">
                        <div class="flex-1 content ms-4">
                            <h5 class="mb-1"><a href="javascript:void(0)" class="text-dark">Simulasi Tryout CAT</a></h5>
                            <p class="text-muted mb-0">Tunggu apalagi, kini kamu juga dapat membeli paket simulasi tryout
                                berbasis CAT di
                                {{ $web->nama_bisnis }}</p>
                            <div class="mt-2">
                                <a href="{{ route('mainweb.product') }}" class="btn btn-sm btn-soft">Lihat Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->

            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- Hero End -->
@endsection
@section('scripts')
    @php
        $breadcrumbItemListPosition = 0;
        $breadcrumbItemList = [
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Home',
                'item' => route('mainweb.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Cek Sertifikat',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
