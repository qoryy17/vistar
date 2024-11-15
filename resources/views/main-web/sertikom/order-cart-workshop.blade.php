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
                        <p class="text-muted mb-0 mx-auto">
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
                                    <th class="border-bottom text-start py-3" style="min-width: 300px;">Produk Item</th>
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
                                        <tr class="shop-list">
                                            <td>
                                                {{ $no }}
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    {{ $row->produk }}
                                                </span>
                                                <br>
                                                Topik : {{ $row->topik }}
                                            </td>
                                            <td class="text-center">
                                                Rp. {{ number_format($row->harga, 0) }}
                                            </td>
                                            </td>
                                            <td style="justify-items: center;">
                                                <div class="d-flex flex-nowrap gap-1">
                                                    <button onclick="hapusItem({{ $no }})"
                                                        class="btn btn-pills btn-soft-danger">
                                                        Hapus <i class="mdi mdi-delete-outline"></i>
                                                    </button>
                                                    <a href="{{ route('orders.sertikom-payment', ['params' => Crypt::encrypt($row->id), 'category' => 'workshop']) }}"
                                                        class="btn btn-pills btn-soft-primary">
                                                        Bayar <i class="mdi mdi-cash-sync"></i>
                                                    </a>
                                                </div>
                                                <form id="formHapusItem{{ $no }}"
                                                    action="{{ route('mainweb.cart-sertikom-delete', ['id' => Crypt::encrypt($row->id), 'category' => 'workshop']) }}"
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

            @if ($recommendProducts->isNotEmpty())
                <div class="row mt-6 border-top">
                    <div class="col-lg-12 col-md-12 mt-4 pt-2">
                        <h2 class="fs-5">Rekomendasi Produk Workshop Pilihan</h2>
                        <p class="text-muted" style="text-align: justify;">
                            Temukan rekomendasi produk pilihan terbaik dalam workshop kami—didesain untuk mendukung
                            kebutuhan
                            Anda dengan kualitas terjamin dan harga spesial. Jangan lewatkan kesempatan untuk mendapatkan
                            penawaran istimewa ini!
                        </p>
                    </div>
                    @foreach ($recommendProducts as $row)
                        @php
                            $image = asset('storage/' . $row->thumbnail);
                            $url = route('mainweb.product-sertikom.training-seminar.show', [
                                'feature' => \App\Enums\FeatureEnum::WORKSHOP->value,
                                'id' => $row->id,
                            ]);
                            $visitor = \App\Models\Sertikom\VisitorProdukModel::where(
                                'ref_produk_id',
                                $row->id,
                            )->count();
                        @endphp

                        <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                            <div class="card blog blog-primary rounded border-0 shadow overflow-hidden" itemscope
                                itemtype="https://schema.org/Product"itemscope itemtype="https://schema.org/Product">
                                <div class="position-relative">
                                    <img itemprop="image" src="{{ $image }}" class="card-img-top"
                                        alt="Thumbnail {{ $image }}" title="Thumbnail {{ $image }}"
                                        loading="eager">
                                </div>
                                <div class="card-body content">
                                    <span class="badge bg-primary mb-2">
                                        <i class="mdi mdi-calendar-range"></i>
                                        {{ \Carbon\Carbon::parse($row->tanggal_mulai)->translatedFormat('F') }}
                                    </span>
                                    <h6>
                                        <a href="#" class="text-primary">{{ $row->topik }}</a>
                                    </h6>
                                    <a itemprop="url" href="{{ $url }}"
                                        title="Lihat Workshop {{ $row->produk }}" class="title text-dark h5">
                                        <span itemprop="name">{{ $row->produk }}</span>
                                    </a>
                                    <p class="text-muted mt-2" style="text-align: justify">
                                        <span class="d-flex fs-5 fw-bold text-primary" itemscope itemprop="offers"
                                            itemtype="https://schema.org/Offer">
                                            <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                            <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                            <span itemprop="price" content="{{ $row->harga }}">
                                                {{ Number::Format($row->harga) }}
                                            </span>
                                        </span>
                                    </p>
                                    <ul class="list-unstyled d-flex justify-content-between border-top mt-3 pt-3 mb-0">
                                        <li class="text-muted small"><i class="uil uil-book-open text-info"></i> Online
                                        </li>
                                        <li class="text-muted small ms-3"><i class="uil uil-eye text-primary"></i>
                                            {{ $visitor }}
                                        </li>
                                    </ul>

                                    <meta itemprop="description" content="{{ $row->deskripsi }}" />

                                    {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                    <div itemscope itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating">
                                        <meta itemprop="ratingValue" content="5" />
                                        <meta itemprop="reviewCount"
                                            content="{{ substr(strval($row->id), -2) + substr(strval($row->id), 0, 2) }}" />
                                    </div>
                                    <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                                        <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                            <meta itemprop="name" content="Qori Chairawan" />
                                        </div>
                                        <meta itemprop="datePublished"
                                            content="{{ \Carbon\Carbon::parse($row->created_at)->addDays(2)->format('Y-m-d') }}" />
                                        <div itemscope itemprop="reviewRating" itemtype="https://schema.org/Rating">
                                            <meta itemprop="worstRating" content="4" />
                                            <meta itemprop="ratingValue" content="5" />
                                            <meta itemprop="bestRating" content="5" />
                                        </div>
                                        <meta itemprop="reviewBody" content="Workshop berbasis online terbaik." />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12 text-center">
                        <a title="Lihat Semua Workshop {{ config('app.name') }}"
                            href="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}"
                            class="btn btn-pills btn-soft-primary">
                            Lihat Semua Workshop <i class="uil uil-arrow-right"></i>
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
