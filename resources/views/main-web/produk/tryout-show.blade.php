 @php
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
 @section('image', asset('storage/tryout/' . $product->thumbnail))
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

             <div class="row">
                 <div class="col-lg-4">
                     <img class="img-fluid" alt="Thumbnail {{ $product->nama_tryout }}"
                         src={{ asset('storage/tryout/' . $product->thumbnail) }} loading="lazy" />
                 </div>
                 <div class="col-lg-8">
                     <h2 class="text-primary fw-bold">
                         {{ $product->nama_tryout }}
                     </h2>
                     @if ($product->category)
                         <p> Kategori :
                             <span class="badge bg-warning">
                                 {{ $product->category->judul }}
                             </span>
                         </p>
                     @endif
                     @if ($product->setting)
                         @php
                             $price = $product->setting->harga;
                             if ($product->setting->harga_promo != null and $product->setting->harga_promo != 0) {
                                 $price = $product->setting->harga_promo;
                             }
                         @endphp
                         <p class="mt-2">
                             <span class="text-muted fs-3 fw-bold">
                                 Rp. {{ number_format($price, 0) }}
                             </span>
                         </p>
                     @endif
                     <div class="d-flex flex-row gap-2">
                         <span>Bagikan :</span>
                         <a href="https://web.facebook.com/share_channel/?link={{ url()->current() }}&source_surface=external_reshare&display&hashtag"
                             target="_blank" class="share-it share-fb">
                             <i class="mdi mdi-facebook"></i>
                             <span>Facebook</span>
                         </a>
                         <a href="https://api.whatsapp.com/send/?text={{ urlencode('Lihat ' . $product->nama_tryout . ' Produk dari ' . config('app.name') . ' disini ' . url()->current()) }}&type=custom_url&app_absent=0"
                             target="_blank" class="share-it share-wa">
                             <i class="mdi mdi-whatsapp"></i>
                             <span>Whatsapp</span>
                         </a>
                         <a href="https://x.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode('Lihat ' . $product->nama_tryout . ' Produk dari ' . config('app.name')) }}"
                             target="_blank" class="share-it share-tw">
                             <i class="mdi mdi-twitter"></i>
                             <span>Twitter / X</span>
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
                 Deskripsi : {!! $product->keterangan !!}
             </div><!--end row-->

             @if ($recommendProducts->isNotEmpty())
                 <div class="row mt-6">
                     <div class="col-lg-12 col-md-12 mt-4 pt-2">
                         <h5>Rekomendasi Produk Tryout Pilihan</h5>
                         <p class="text-muted">
                             Jangan Lewatkan Kesempatan Ini! Pilih Paket Tryout yang Sesuai dengan Target Anda dan
                             Bersiaplah
                             untuk Sukses di Ujian!
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
                         @endphp
                         <div class="col-lg-4 col-md-6">
                             <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                 <div class="card-body p-0">
                                     <div class="d-inline-block">
                                         <img class="img-fluid mb-3" src="{{ asset('storage/tryout/' . $row->thumbnail) }}"
                                             alt="Thubmnail {{ $row->nama_tryout }}" loading="lazy" />
                                     </div>
                                     <span
                                         class="text-center py-2 px-2 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-md">
                                         {{ $row->nama_tryout }}
                                     </span>
                                     <h3 class="fw-bold mb-0 mt-3">
                                         Rp. {{ number_format($row->harga, 0) }}
                                         @if ($row->harga_promo != null && $row->harga_promo != 0)
                                             <p class="text-muted">
                                                 Promo Rp. {{ number_format($row->harga_promo, 0) }}
                                             </p>
                                         @endif
                                     </h3>
                                     <div class="accordion" id="buyingquestion">
                                         <div class="accordion-item rounded">
                                             <h2 class="accordion-header" id="headingOne{{ $row->id }}">
                                                 <button class="accordion-button border-0 bg-light" type="button"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#collapseOne{{ $row->id }}" aria-expanded="true"
                                                     aria-controls="collapseOne{{ $row->id }}">
                                                     Fitur dalam paket ini
                                                 </button>
                                             </h2>
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

                                                         <li class="h6 text-muted mb-0">
                                                             <span class="icon h5 me-2">
                                                                 <i class="uil uil-check-circle align-middle"></i>
                                                             </span>
                                                             Masa Aktif {{ $row->masa_aktif }} Hari
                                                         </li>
                                                     </ul>
                                                 </div>
                                             </div>
                                         </div>

                                     </div>
                                     <div class="mt-4">
                                         <div class="d-grid">
                                             <a href="{{ route('mainweb.product-show', ['id' => $row->id]) }}"
                                                 class="btn btn-pills btn-primary">
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
                         <a href="{{ route('mainweb.product') }}" class="btn btn-pills btn-soft-primary">
                             Lihat Semua Produk <i class="uil uil-arrow-right"></i>
                         </a>
                     </div>
                 </div>
             @endif
         </div><!--end container-->
     </section><!--end section-->
 @endsection
