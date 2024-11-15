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
                            {{ $descriptionPage }}
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <!-- Filter Pencarian Produk Tryout -->
            <form action="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="searchExpertise">Pilih Topik Workshop</label>
                            <select name="expertise_id" class="form-control" id="searchExpertise">
                                <option value="">-- Pilih Topik Workshop --</option>
                                @foreach ($expertises as $expertise)
                                    <option {{ $searchExpertiseId == $expertise->id ? 'selected' : '' }}
                                        value="{{ $expertise->id }}">
                                        {{ $expertise->topik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 mb-2">
                        <div class="form-group">
                            <label for="searchName">Cari Workshop</label>
                            <input type="text" autocomplete="off" placeholder="Cari Workshop..." id="searchName"
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
                                <a href="{{ route('mainweb.product-sertikom', ['category' => 'workshop']) }}"
                                    class="btn btn-block btn-pills btn-warning">
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
                            <strong>Informasi</strong> Maaf Workshop Tidak Ditemukan...!!!
                        </div>
                    </div>
                @else
                    @foreach ($products as $row)
                        @php
                            $features = [];

                            $url = route('mainweb.product-sertikom.training-seminar.show', [
                                'feature' => \App\Enums\FeatureEnum::WORKSHOP->value,
                                'id' => $row->id,
                            ]);
                            $visitor = \App\Models\Sertikom\VisitorProdukModel::where(
                                'ref_produk_id',
                                $row->id,
                            )->count();
                            $image = asset('storage/' . $row->thumbnail);
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
                                    <a itemprop="url" href="{{ $url }}" class="title text-dark h5">
                                        <span itemprop="name">{{ $row->produk }}</span>
                                    </a>
                                    <p class="text-muted mt-2" style="text-align: justify">

                                        <span class="d-flex fs-5 fw-bold text-primary" itemscope itemprop="offers"
                                            itemtype="https://schema.org/Offer">
                                            <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                            <span itemprop="priceCurrency" content="IDR">Rp. </span>
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
                                        <meta itemprop="reviewBody" content="Workshop IT berbasis online terbaik." />
                                    </div>
                                </div>
                            </div>
                        </div>
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
                'name' => 'Produk Workshop',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
