 @php
     $promoCode = \App\Http\Controllers\PromoCodeController::getPromoCode();

     $tags = ['</p>', '<br />', '<br>', '<hr />', '<hr>', '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>'];

     $descriptionPlainText = trim(strip_tags(str_replace($tags, '. ', $product->keterangan)));
     if (strlen($descriptionPlainText) > 170) {
         $descriptionPlainText = substr($descriptionPlainText, 0, 167) . '...';
     }

     $keywords = [];
     if ($product->category) {
         if (!in_array($product->category->judul, $keywords)) {
             array_push($keywords, $product->category->judul);
         }
     }
     if (!in_array($product->nama_tryout, $keywords)) {
         array_push($keywords, $product->nama_tryout);
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
                 $price = null;
                 $normalPrice = null;
                 $productSetting = $product->setting;
                 if ($productSetting) {
                     $price = $productSetting->harga;
                     $normalPrice = $price;
                     if ($productSetting->harga_promo != null && $productSetting->harga_promo != 0) {
                         $price = $productSetting->harga_promo;
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
                 }

                 $image = asset('storage/' . $product->thumbnail);
             @endphp
             <div itemscope itemtype="https://schema.org/Product">
                 <div class="row">
                     <div class="col-lg-4">
                         <img itemprop="image" class="img-fluid mb-2" src="{{ $image }}"
                             alt="Thumbnail {{ $product->nama_tryout }}" title="Thumbnail {{ $product->nama_tryout }}"
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
                                 <meta itemprop="name" content="Ahmad Yusri" />
                             </div>
                             <meta itemprop="datePublished"
                                 content="{{ \Carbon\Carbon::parse($product->created_at)->addDays(2)->format('Y-m-d') }}" />
                             <div itemscope itemprop="reviewRating" itemtype="https://schema.org/Rating">
                                 <meta itemprop="worstRating" content="4" />
                                 <meta itemprop="ratingValue" content="5" />
                                 <meta itemprop="bestRating" content="5" />
                             </div>
                             <meta itemprop="reviewBody" content="Soal Ujian yang lengkap dan terbaru." />
                         </div>

                         <h1 itemprop="name" class="text-primary fs-2 fw-bold">
                             {{ $product->nama_tryout }}
                         </h1>
                         @if ($product->category)
                             <p> Kategori :
                                 <span class="badge bg-warning">
                                     {{ $product->category->judul }}
                                 </span>
                             </p>
                         @endif
                         @if ($price !== null)
                             <div class="my-3" itemscope itemprop="offers" itemtype="https://schema.org/Offer">
                                 <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                 <p class="fs-2 fw-bold mb-0 mt-3 d-flex gap-2 lh-1">
                                     <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                     <span itemprop="price" content="{{ $price }}">
                                         {{ number_format($price, 0) }}
                                     </span>
                                 </p>
                                 @if ($normalPrice > $price)
                                     <p class="text-muted text-decoration-line-through">
                                         Harga Normal Rp. {{ number_format($normalPrice, 0) }}
                                     </p>
                                 @endif
                             </div>
                         @endif
                         <div class="d-flex flex-row gap-2">
                             <span>Bagikan :</span>
                             <a title="Bagikan {{ $product->nama_tryout }} ke Facebook"
                                 href="https://web.facebook.com/share_channel/?link={{ url()->current() }}&source_surface=external_reshare&display&hashtag"
                                 target="_blank" class="share-it share-fb">
                                 <i class="mdi mdi-facebook"></i>
                                 <span class="fw-bold">Facebook</span>
                             </a>
                             <a title="Bagikan {{ $product->nama_tryout }} ke Whatsapp"
                                 href="https://api.whatsapp.com/send/?text={{ urlencode('Lihat ' . $product->nama_tryout . ' Produk dari ' . config('app.name') . ' disini ' . url()->current()) }}&type=custom_url&app_absent=0"
                                 target="_blank" class="share-it share-wa">
                                 <i class="mdi mdi-whatsapp"></i>
                                 <span class="fw-bold">Whatsapp</span>
                             </a>
                             <a title="Bagikan {{ $product->nama_tryout }} ke Twitter/X"
                                 href="https://x.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode('Lihat ' . $product->nama_tryout . ' Produk dari ' . config('app.name')) }}"
                                 target="_blank" class="share-it share-tw">
                                 <i class="mdi mdi-twitter"></i>
                                 <span class="fw-bold">X</span>
                             </a>
                         </div>
                         @if (@$product->category->status === 'Berbayar')
                             <div class="mt-4">
                                 @if ($order)
                                     <form
                                         action="{{ route('ujian.main', ['id' => Crypt::encrypt($order->id), 'param' => Crypt::encrypt('berbayar')]) }}"
                                         method="POST">
                                         @csrf
                                         @method('POST')
                                         <button type="submit" class="btn btn-pills btn-primary d-block d-md-inline-block">
                                             Mulai Ujian <i class="mdi mdi-arrow-right"></i>
                                         </button>
                                     </form>
                                 @else
                                     <form
                                         action="{{ route('mainweb.pesan-tryout-berbayar', ['idProdukTryout' => Crypt::encrypt($product->id)]) }}"
                                         method="POST">
                                         @csrf
                                         @method('POST')

                                         <button type="submit" class="btn btn-pills btn-primary d-block d-md-inline-block">
                                             Beli Sekarang <i class="mdi mdi-arrow-right"></i>
                                         </button>
                                     </form>
                                 @endif
                             </div>
                         @endif
                     </div>
                 </div><!--end row-->

                 <div class="mt-4 border-top">
                     @php
                         $features = [];
                         if ($product->setting) {
                             if ($product->setting->nilai_keluar === 'Y') {
                                 array_push($features, 'Hasil Ujian');
                             }
                             if ($product->setting->grafik_evaluasi === 'Y') {
                                 array_push($features, 'Grafik Hasil Ujian');
                             }
                             if ($product->setting->review_pembahasan === 'Y') {
                                 array_push($features, 'Review Pembahasan Soal');
                             }
                             if (!$order) {
                                 array_push($features, 'Masa Aktif ' . $product->setting->masa_aktif . ' Hari');
                             }
                         }
                     @endphp
                     @if (count($features) > 0)
                         <ul class="list-unstyled pt-3">
                             @foreach ($features as $item)
                                 <li class="h6 text-muted mb-0">
                                     <span class="icon h5 me-2">
                                         <i class="uil uil-check-circle align-middle"></i>
                                     </span>
                                     {{ $item }}
                                 </li>
                             @endforeach
                         </ul>
                     @endif
                     <div itemprop="description">
                         {!! $product->keterangan !!}
                     </div>
                 </div><!--end row-->
             </div>

             @if ($recommendProducts->isNotEmpty())
                 <br />
                 <div class="row mt-6 border-top">
                     <div class="col-lg-12 col-md-12 mt-4 pt-2">
                         <h2 class="fs-5">Rekomendasi Produk Pilihan</h2>
                         <p class="text-muted">
                             Jangan Lewatkan Kesempatan Ini! Pilih Produk yang Sesuai dengan Target Anda dan
                             Bersiaplah untuk Sukses!
                         </p>
                     </div>
                     @foreach ($recommendProducts as $row)
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

                             $image = asset('storage/' . $row->thumbnail);
                             $url = route('mainweb.product-show', ['id' => $row->id]);
                         @endphp

                         <div class="col-lg-4 col-md-6" itemscope itemtype="https://schema.org/Product">
                             <meta itemprop="description" content="{{ $row->keterangan }}" />
                             <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                 <div class="card-body p-0">
                                     <div class="d-inline-block">
                                         <img itemprop="image" class="img-fluid mb-3" src="{{ $image }}"
                                             alt="Thumbnail {{ $row->nama_tryout }}"
                                             title="Thumbnail {{ $row->nama_tryout }}" loading="lazy" />
                                     </div>
                                     <h3 itemprop="name"
                                         class="text-center py-2 px-2 d-inline-block bg-soft-primary fs-6 mb-0 text-primary rounded-md">
                                         {{ $row->nama_tryout }}
                                     </h3>
                                     {{--  IDEA: get testimoni data from user testimoni, currently set to manual  --}}
                                     <div itemscope itemprop="aggregateRating"
                                         itemtype="https://schema.org/AggregateRating">
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

                                     <div class="my-3" itemscope itemprop="offers" itemtype="https://schema.org/Offer">
                                         <meta itemprop="availability" content="https://schema.org/OnlineOnly" />
                                         <p class="fs-4 fw-bold m-0">
                                             <span itemprop="priceCurrency" content="IDR">Rp.</span>
                                             <span itemprop="price" content="{{ $price }}">
                                                 {{ number_format($price, 0) }}
                                             </span>
                                         </p>
                                         @if ($normalPrice > $price)
                                             <p class="text-muted text-decoration-line-through">
                                                 Harga Normal Rp. {{ number_format($price, 0) }}
                                             </p>
                                         @endif
                                     </div>

                                     <div class="accordion" id="buyingquestion">
                                         <div class="accordion-item rounded">
                                             <p class="accordion-header" id="headingOne{{ $row->id }}">
                                                 <button class="accordion-button border-0 bg-light" type="button"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#collapseOne{{ $row->id }}"
                                                     aria-expanded="true" aria-controls="collapseOne{{ $row->id }}">
                                                     Fitur dalam paket ini
                                                 </button>
                                             </p>
                                             <div id="collapseOne{{ $row->id }}"
                                                 class="accordion-collapse border-0 collapse "
                                                 aria-labelledby="headingOne{{ $row->id }}"
                                                 data-bs-parent="#buyingquestion">
                                                 <div class="accordion-body text-muted">
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
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="mt-4">
                                         <div class="d-grid">
                                             <a itemprop="url" title="Lihat Produk {{ $row->nama_tryout }}"
                                                 href="{{ $url }}" class="btn btn-pills btn-primary">
                                                 Lihat <i class="fa fa-chevron-right"></i>
                                             </a>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div><!--end col-->
                     @endforeach
                 </div>
                 <div class="row mt-5">
                     <div class="col-lg-12 text-center">
                         <a title="Lihat Semua Produk {{ config('app.name') }}" href="{{ route('mainweb.product') }}"
                             class="btn btn-pills btn-soft-primary">
                             Lihat Semua Produk <i class="uil uil-arrow-right"></i>
                         </a>
                     </div>
                 </div>
             @endif
         </div><!--end container-->
     </section><!--end section-->
 @endsection
