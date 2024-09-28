@php
    $promoCode = \App\Http\Controllers\PromoCodeController::getPromoCode();
@endphp
@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Start -->
    <section class="section" style="margin-top: 50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h1 class="fs-3 title mb-4">Keranjang Pesanan Anda</h1>
                        <p class="text-muted para-desc mb-0 mx-auto">
                            Berikut keranjang pesanan produk, anda dapat melakukan pembayaran dan juga menghapus
                            item produk keranjang
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            @if (session()->has('successMessage'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert bg-soft-primary fw-medium" role="alert"> <i
                                class="uil uil-info-circle fs-5 align-middle me-1"></i>
                            {{ session('successMessage') }}
                        </div>
                    </div>
                </div>
            @elseif (session()->has('errorMessage'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert bg-soft-danger fw-medium" role="alert"> <i
                                class="uil uil-info-circle fs-5 align-middle me-1"></i>
                            {{ session('errorMessage') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive bg-white shadow rounded">
                        <table class="table mb-0 table-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom text-start py-3" style="min-width: 10px;">No</th>
                                    <th class="border-bottom text-start py-3" style="min-width: 300px;">Produk</th>
                                    <th class="border-bottom text-center py-3" style="min-width: 150px;">Harga</th>
                                    <th class="border-bottom text-center py-3" style="min-width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cartItems->count() > 0)
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($cartItems as $row)
                                        @php
                                            $price = $row->harga;
                                            $normalPrice = $price;
                                            if ($row->harga_promo != null && $row->harga_promo != 0) {
                                                $price = $row->harga_promo;
                                            }

                                            // Apply promo code
                                            if ($promoCode) {
                                                if ($promoCode['promo']['type'] === 'percent') {
                                                    $normalPrice = $price;
                                                    $price = $price - ($price * $promoCode['promo']['value']) / 100;
                                                } elseif ($promoCode['promo']['type'] === 'deduction') {
                                                    if ($promoCode['promo']['type'] === 'percent') {
                                                        $normalPrice = $price;
                                                        $price = $price - $promoCode['promo']['value'];
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr class="shop-list">
                                            <td>
                                                {{ $no }}
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    {{ $row->nama_tryout }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                Rp. {{ number_format($price, 0) }}
                                                @if ($normalPrice > $price)
                                                    <p class="mb-0 text-muted text-decoration-line-through">
                                                        Harga Normal Rp. {{ number_format($normalPrice, 0) }}
                                                    </p>
                                                @endif
                                            </td>
                                            </td>
                                            <td class="d-flex justify-content-center">
                                                <div class="d-flex flex-nowrap gap-1">
                                                    <button onclick="hapusItem({{ $no }})"
                                                        class="btn btn-pills btn-soft-danger">Hapus
                                                    </button>
                                                    <a href="{{ route('orders.detail-pesanan', ['params' => Crypt::encrypt($row->id)]) }}"
                                                        class="btn btn-pills btn-soft-primary">Bayar</a>
                                                </div>
                                                <form id="formHapusItem{{ $no }}"
                                                    action="{{ route('mainweb.hapus-item', ['id' => Crypt::encrypt($row->id)]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert bg-soft-warning fw-medium" role="alert"> <i
                                                    class="uil uil-info-circle fs-5 align-middle me-1"></i>
                                                Anda belum menambahkan produk apapun dikeranjang ini !
                                            </div>

                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            @if ($recommendProducts->count() > 0)
                <div class="row mt-6">
                    <div class="col-lg-12 col-md-12 mt-4 pt-2">
                        <h2 class="fs-5">Rekomendasi Produk Pilihan</h2>
                        <p class="text-muted">
                            Jangan Lewatkan Kesempatan Ini! Pilih Produk yang Sesuai dengan Target Anda dan
                            Bersiaplah untuk Sukses!
                        </p>
                    </div>
                    @foreach ($recommendProducts as $row)
                        @php
                            $price = $row->harga;
                            $normalPrice = $price;
                            if ($row->harga_promo != null && $row->harga_promo != 0) {
                                $price = $row->harga_promo;
                            }

                            // Apply promo code
                            if ($promoCode) {
                                if ($promoCode['promo']['type'] === 'percent') {
                                    $normalPrice = $price;
                                    $price = $price - ($price * $promoCode['promo']['value']) / 100;
                                } elseif ($promoCode['promo']['type'] === 'deduction') {
                                    if ($promoCode['promo']['type'] === 'percent') {
                                        $normalPrice = $price;
                                        $price = $price - $promoCode['promo']['value'];
                                    }
                                }
                            }
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                <div class="card-body p-0">
                                    <div class="d-inline-block">
                                        <img class="img-fluid mb-3" src="{{ asset('storage/' . $row->thumbnail) }}"
                                            alt="Thumbnail {{ $row->nama_tryout }}"
                                            title="Thumbnail {{ $row->nama_tryout }}" loading="lazy" />
                                    </div>
                                    <h3
                                        class="text-center py-2 px-2 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-md">
                                        {{ $row->nama_tryout }}
                                    </h3>
                                    <p class="fs-4 fw-bold mb-0 mt-3">
                                        Rp. {{ number_format($price, 0) }}</h2>
                                        @if ($normalPrice > $price)
                                            <p class="text-muted text-decoration-line-through">
                                                Harga Normal Rp. {{ number_format($normalPrice, 0) }}
                                            </p>
                                        @endif
                                    <div class="accordion" id="buyingquestion">
                                        <div class="accordion-item rounded">
                                            <p class="accordion-header" id="headingOne{{ $row->id }}">
                                                <button class="accordion-button border-0 bg-light" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOne{{ $row->id }}" aria-expanded="true"
                                                    aria-controls="collapseOne{{ $row->id }}">
                                                    Fitur dalam paket ini
                                                </button>
                                            </p>
                                            <div id="collapseOne{{ $row->id }}"
                                                class="accordion-collapse border-0 collapse "
                                                aria-labelledby="headingOne{{ $row->id }}"
                                                data-bs-parent="#buyingquestion">
                                                <div class="accordion-body text-muted">
                                                    <ul class="list-unstyled pt-3 border-top">
                                                        @if ($row->nilai_keluar == 'Y')
                                                            <li class="h6 text-muted mb-0">
                                                                <span class="icon h5 me-2">
                                                                    <i class="uil uil-check-circle align-middle"></i>
                                                                </span>
                                                                Hasil Ujian
                                                            </li>
                                                        @endif

                                                        @if ($row->grafik_evaluasi == 'Y')
                                                            <li class="h6 text-muted mb-0">
                                                                <span class="icon h5 me-2">
                                                                    <i class="uil uil-check-circle align-middle"></i>
                                                                </span>
                                                                Grafik Hasil Ujian
                                                            </li>
                                                        @endif

                                                        @if ($row->review_pembahasan == 'Y')
                                                            <li class="h6 text-muted mb-0">
                                                                <span class="icon h5 me-2">
                                                                    <i class="uil uil-check-circle align-middle"></i>
                                                                </span>
                                                                Review Pembahasan Soal
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mt-4">
                                        <div class="d-grid">
                                            <form
                                                action="{{ route('mainweb.pesan-tryout-berbayar', ['idProdukTryout' => Crypt::encrypt($row->id)]) }}"
                                                method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-pills btn-primary">
                                                    Beli Sekarang
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    @endforeach
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12 text-center">
                        <a href="{{ route('mainweb.product') }}" class="btn btn-pills btn-soft-primary">
                            Lihat Semua Produk <i class="uil uil-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <script>
        function hapusItem(no) {
            document.getElementById(`formHapusItem${no}`).submit();
        }
    </script>
@endsection
