@php
    $promoCode = \App\Http\Controllers\PromoCodeController::getPromoCode();
@endphp
@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <section class="section" style="margin-top: 50px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h1 class="title mb-4">{{ $title }}</h1>
                        <p class="text-muted mb-0 mx-auto">
                            Temukan produk yang sempurna untuk Anda!
                            Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan,
                            <span class="text-primary fw-bold">Vistar Indonesia</span>
                            memberikan solusi terbaik untuk persiapan ujian Anda.
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <!-- Filter Pencarian Produk Tryout -->
            <form action="{{ route('mainweb.product') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="searchCategory">Pilih Paket Tryout</label>
                            <select name="category_id" class="form-control" id="searchCategory">
                                <option value="">-- Pilih Paket Tryout --</option>
                                @foreach ($categories as $category)
                                    <option {{ $searchCategoryId == $category->id ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 mb-2">
                        <div class="form-group">
                            <label for="searchName">Cari Paket Tryout</label>
                            <input type="text" autocomplete="off" placeholder="Cari Paket Tryout..." id="searchName"
                                class="form-control" name="search_name" value="{{ $searchName ? $searchName : '' }}" />
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-group">
                            <label for="">Filter / Cari / Reset</label>
                            <div>
                                <button type="submit" class="btn btn-block btn-pills btn-primary">
                                    <i class="mdi mdi-search-web"></i>
                                    Filter
                                </button>
                                <a href="{{ route('mainweb.product') }}" class="btn btn-block btn-pills btn-warning">
                                    <i class="mdi mdi-refresh"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                @if ($products->isEmpty())
                    <div class="col-lg-12 col-md-12 mt-4 pt-2">
                        <div class="alert bg-soft-warning fw-medium fade show" role="alert">
                            <i class="uil uil-info-circle fs-5 align-middle me-1"></i>
                            <strong>Informasi</strong> Maaf Paket Tryout Tidak Ditemukan...!!!
                        </div>
                    </div>
                @else
                    @foreach ($products as $row)
                        @php
                            $features = [];

                            if ($row->nilai_keluar === 'Y') {
                                array_push($features, 'Hasil Ujian');
                            }
                            if ($row->grafik_evaluasi === 'Y') {
                                array_push($features, 'Grafik Hasil Ujian');
                            }
                            if ($row->review_pembahasan === 'Y') {
                                array_push($features, 'Review Pembahasan Soal');
                            }

                            $url = route('mainweb.product.tryout.show', [
                                'feature' => \App\Enums\FeatureEnum::TRYOUT->value,
                                'id' => $row->id,
                            ]);
                            $image = asset('storage/' . $row->thumbnail);
                        @endphp
                        <div class="col-lg-4 col-md-6 mt-4 pt-2">
                            <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                <div class="card-body p-0" itemscope itemtype="https://schema.org/Product">
                                    <a title="{{ $row->nama_tryout }}" href="{{ $url }}" class="d-inline-block">
                                        <img itemprop="image" class="img-fluid mb-3" src="{{ $image }}"
                                            alt="Thumbnail {{ $row->nama_tryout }}"
                                            title="Thumbnail {{ $row->nama_tryout }}" loading="lazy" />
                                    </a>
                                    <h2>
                                        <a itemprop="url" title="{{ $row->nama_tryout }}" href="{{ $url }}"
                                            class="py-2 px-2 d-inline-block h5 mb-0 text-primary">
                                            <span itemprop="name">{{ $row->nama_tryout }}</span>
                                        </a>
                                    </h2>
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
                                    <div class="mb-3" itemscope itemprop="offers" itemtype="https://schema.org/Offer">
                                        <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                        <p class="fs-2 fw-bold mb-0 d-flex gap-2 lh-1">
                                            <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                            <span itemprop="price" content="{{ $price }}">
                                                {{ number_format($price, 0) }}
                                            </span>
                                        </p>
                                        @if ($normalPrice > $price)
                                            <p class="mb-0 text-muted text-decoration-line-through">
                                                Harga Normal Rp. {{ number_format($normalPrice, 0) }}
                                            </p>
                                        @endif
                                    </div>

                                    <p class="text-muted">Fitur dalam paket ini</p>

                                    <ul class="list-unstyled pt-3 border-top">
                                        @foreach ($features as $item)
                                            <li class="h6 text-muted mb-0">
                                                <span class="icon h5 me-2">
                                                    <i class="uil uil-check-circle align-middle"></i>
                                                </span>
                                                {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-4">
                                        <div class="d-grid">
                                            <form
                                                action="{{ route('mainweb.pesan-tryout-berbayar', ['idProdukTryout' => Crypt::encrypt($row->id)]) }}"
                                                method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-pills btn-primary">
                                                    Beli Sekarang <i class="mdi mdi-arrow-right"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <meta itemprop="description" content="{{ $row->keterangan }}" />

                                    {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                    <div itemscope itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating">
                                        <meta itemprop="ratingValue" content="5" />
                                        <meta itemprop="reviewCount"
                                            content="{{ substr(strval($row->id), -2) + substr(strval($row->id), 0, 2) }}" />
                                    </div>
                                    <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                                        <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                            <meta itemprop="name" content="Ahmad Yusri" />
                                        </div>
                                        <meta itemprop="datePublished"
                                            content="{{ \Carbon\Carbon::parse($row->created_at)->addDays(2)->format('Y-m-d') }}" />
                                        <div itemscope itemprop="reviewRating" itemtype="https://schema.org/Rating">
                                            <meta itemprop="worstRating" content="4" />
                                            <meta itemprop="ratingValue" content="5" />
                                            <meta itemprop="bestRating" content="5" />
                                        </div>
                                        <meta itemprop="reviewBody" content="Soal Ujian yang lengkap dan terbaru." />
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    @endforeach
                    <div class="mt-5 table-responsive">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
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
                'name' => 'Produk Tryout Simulasi CAT',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
