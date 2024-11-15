@php
    $tags = ['</p>', '<br />', '<br>', '<hr />', '<hr>', '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>'];

    $descriptionPlainText = trim(strip_tags(str_replace($tags, '. ', $product->deskripsi)));
    if (strlen($descriptionPlainText) > 170) {
        $descriptionPlainText = substr($descriptionPlainText, 0, 167) . '...';
    }

    $keywords = [];
    if ($product->expertise) {
        if (!in_array($product->expertise->topik, $keywords)) {
            array_push($keywords, $product->expertise->topik);
        }
    }
    if (!in_array($product->produk, $keywords)) {
        array_push($keywords, $product->produk);
    }
@endphp
@extends('main-web.layout.main')
@section('title', $title)
@section('image', asset('storage/' . $product->thumbnail))
@section('description', $descriptionPlainText)
@section('keywords', implode(', ', $keywords))
@section('content')
    <section class="section" style="margin-top: 50px;">
        <div class="container">
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

            @php
                $image = asset('storage/' . $product->thumbnail);
            @endphp
            <div itemscope itemtype="https://schema.org/Product">
                <div class="row">
                    <div class="col-lg-4">
                        <img itemprop="image" class="img-fluid mb-2" src="{{ $image }}"
                            alt="Thumbnail {{ $product->produk }}" title="Thumbnail {{ $product->produk }}"
                            loading="lazy" />
                    </div>
                    <div class="col-lg-8">
                        <meta itemprop="url" content="{{ url()->current() }}" />

                        {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                        <div itemscope itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating">
                            <meta itemprop="ratingValue" content="5" />
                            <meta itemprop="reviewCount"
                                content="{{ substr(strval($product->id), -2) + substr(strval($product->id), 0, 2) }}" />
                        </div>
                        <div itemscope itemprop="review" itemtype="https://schema.org/Review">
                            <div itemscope itemprop="author" itemtype="https://schema.org/Person">
                                <meta itemprop="name" content="Qori Chairawan" />
                            </div>
                            <meta itemprop="datePublished"
                                content="{{ \Carbon\Carbon::parse($product->created_at)->addDays(2)->format('Y-m-d') }}" />
                            <div itemscope itemprop="reviewRating" itemtype="https://schema.org/Rating">
                                <meta itemprop="worstRating" content="4" />
                                <meta itemprop="ratingValue" content="5" />
                                <meta itemprop="bestRating" content="5" />
                            </div>
                            <meta itemprop="reviewBody" content="Seminar/Workshop IT berbasis online terbaik." />
                        </div>

                        <h1 itemprop="name" class="text-primary fs-4 fw-bold">
                            {{ $product->produk }} <sup class="badge bg-success" style="font-size: 10px;">Online</sup>
                        </h1>
                        @if ($product->expertise)
                            <p> Topik Keahlian :
                                <span class="badge bg-warning">
                                    {{ $product->expertise->topik }}
                                </span>
                            </p>
                        @endif
                        @if ($product->harga !== null)
                            <div class="my-3" itemscope itemprop="offers" itemtype="https://schema.org/Offer">
                                <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                <p class="fs-2 fw-bold mb-0 mt-3 d-flex gap-2 lh-1">
                                    <span itemprop="priceCurrency" content="IDR">Rp. </span>
                                    <span itemprop="price" content="{{ $product->harga }}">
                                        {{ number_format($product->harga, 0) }}
                                    </span>
                                </p>
                            </div>
                        @endif
                        <div class="d-flex flex-row gap-2">
                            <span>Bagikan :</span>
                            <a title="Bagikan {{ $product->produk }} ke Facebook"
                                href="https://web.facebook.com/share_channel/?link={{ url()->current() }}&source_surface=external_reshare&display&hashtag"
                                target="_blank" class="share-it share-fb">
                                <i class="mdi mdi-facebook"></i>
                                <span class="fw-bold">Facebook</span>
                            </a>
                            <a title="Bagikan {{ $product->produk }} ke Whatsapp"
                                href="https://api.whatsapp.com/send/?text={{ urlencode('Lihat ' . $product->produk . ' Seminar/Workshop dari ' . config('app.name') . ' disini ' . url()->current()) }}&type=custom_url&app_absent=0"
                                target="_blank" class="share-it share-wa">
                                <i class="mdi mdi-whatsapp"></i>
                                <span class="fw-bold">Whatsapp</span>
                            </a>
                            <a title="Bagikan {{ $product->produk }} ke Twitter/X"
                                href="https://x.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode('Lihat ' . $product->produk . ' Seminar/Workshop dari ' . config('app.name')) }}"
                                target="_blank" class="share-it share-tw">
                                <i class="mdi mdi-twitter"></i>
                                <span class="fw-bold">X</span>
                            </a>
                        </div>
                        @if (@$product->category->status === 'Berbayar')
                            <div class="mt-4">
                                @if ($order)
                                    <a href="{{ route('customer.get-sertikom', ['category' => 'seminar']) }}"
                                        title="{{ $product->produk }}"
                                        class="btn btn-pills btn-soft-primary d-block d-md-inline-block">
                                        Lihat Seminar <i class="mdi mdi-arrow-right"></i>
                                    </a>
                                @else
                                    @if (date('Y-m-d') > $product->tanggal_mulai)
                                        <div class="alert bg-soft-warning fw-medium" role="alert">
                                            <i class="uil uil-info-circle fs-5 align-middle me-1"></i>
                                            Seminar ini sedang berlangsung tidak dapat dipesan !
                                        </div>
                                    @else
                                        <form
                                            action="{{ route('mainweb.cart-sertikom-add', ['idProdukSertikom' => Crypt::encrypt($product->id), 'category' => 'seminar']) }}"
                                            method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="submit"
                                                class="btn btn-pills btn-soft-primary d-block d-md-inline-block">
                                                Daftar Sekarang <i class="mdi mdi-arrow-right"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div><!--end row-->
                <div class="mt-4 mb-2 border-top">
                    <h5 class="mt-3">Informasi Seminar</h5>
                    <p style="text-align: justify;">
                        {!! $product->deskripsi !!}
                    </p>

                    <h5 class="mt-3">Benefit Seminar</h5>
                    @php
                        $benefit = App\Helpers\BerandaUI::benefitSeminar();
                    @endphp
                    <ul class="list-unstyled">
                        @foreach ($benefit as $listBenefit)
                            <li class="mb-0">
                                <span class="icon me-2">
                                    <i class="uil uil-check-circle align-middle"></i>
                                </span>
                                {{ $listBenefit }}
                            </li>
                        @endforeach
                    </ul>

                    <h5 class="mt-3">Jadwal Seminar</h5>
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $product->tanggal_mulai)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $product->tanggal_selesai)->format('d/m/Y') }}
                </div>
            </div>

            @if ($recommendProducts->isNotEmpty())
                <div class="row mt-6 border-top">
                    <div class="col-lg-12 col-md-12 mt-4 pt-2">
                        <h2 class="fs-5">Rekomendasi Produk Seminar Pilihan</h2>
                        <p class="text-muted" style="text-align: justify;">
                            Temukan rekomendasi produk pilihan terbaik dalam seminar kami—didesain untuk mendukung kebutuhan
                            Anda dengan kualitas terjamin dan harga spesial. Jangan lewatkan kesempatan untuk mendapatkan
                            penawaran istimewa ini!
                        </p>
                    </div>
                    @foreach ($recommendProducts as $row)
                        @php
                            $image = asset('storage/' . $row->thumbnail);
                            $url = route('mainweb.product-sertikom.training-seminar.show', [
                                'feature' => \App\Enums\FeatureEnum::SEMINAR->value,
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
                                        title="Lihat Seminar {{ $row->produk }}" class="title text-dark h5">
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
                                    <div itemscope itemprop="aggregateRating"
                                        itemtype="https://schema.org/AggregateRating">
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
                                        <meta itemprop="reviewBody" content="Seminar IT berbasis online terbaik." />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-5">
                    <div class="col-lg-12 text-center">
                        <a title="Lihat Semua Seminar {{ config('app.name') }}"
                            href="{{ route('mainweb.product-sertikom', ['category' => 'Seminar']) }}"
                            class="btn btn-pills btn-soft-primary">
                            Lihat Semua Seminar <i class="uil uil-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif
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
                'name' => 'Produk Seminar',
                'item' => route('mainweb.product-sertikom', ['category' => 'Seminar']),
            ],
            [
                '@type' => 'ListItem',
                'position' => ++$breadcrumbItemListPosition,
                'name' => 'Detail Seminar',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
