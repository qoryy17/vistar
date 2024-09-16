 @extends('main-web.layout.main')
 @section('title', $title)
 @section('content')
     <section class="section" style="padding-top: 100px;">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-12 text-center">
                     <div class="section-title mb-4 pb-2">
                         <h4 class="title mb-4">Bidang Kompetensi Pilih Paket Ujian</h4>
                         <p class="text-muted para-desc mb-0 mx-auto">
                             Temukan paket ujian tryout yang sempurna untuk Anda!
                             Dengan berbagai pilihan paket yang dirancang sesuai kebutuhan,
                             <span class="text-primary fw-bold">Vistar Indonesia</span> memberikan
                             solusi terbaik untuk persiapan ujian Anda.
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
                     <div class="col-md-4 mb-2">
                         <div class="form-group">
                             <label for="searchName">Cari Paket Tryout</label>
                             <input type="text" autocomplete="off" placeholder="Cari Paket Tryout..." id="searchName"
                                 class="form-control" name="search_name" value="{{ $searchName ? $searchName : '' }}" />
                         </div>
                     </div>
                     <div class="col-md-4 mb-2">
                         <div class="form-group">
                             <label for="">Filter / Cari / Reset</label>
                             <div>
                                 <button type="submit" class="btn btn-pills btn-primary btn-block ">
                                     <i class="mdi mdi-search-web"></i>
                                     Filter
                                 </button>
                                 <a href="{{ route('mainweb.product') }}" class="btn btn-pills btn-warning btn-block ">
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
                         <div class="alert alert-warning alert-dismissible fade show" role="alert">
                             <strong>Informasi</strong> Maaf Paket Tryout Tidak Ditemukan...!!!
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"> </button>
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
                         @endphp
                         <div class="col-lg-4 col-md-6 mt-4 pt-2">
                             <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                                 <div class="card-body p-0">
                                     <a href="{{ route('mainweb.product-show', ['id' => $row->id]) }}"
                                         class="d-inline-block">
                                         <img class="img-fluid mb-3" src="{{ asset('storage/tryout/' . $row->thumbnail) }}"
                                             alt="Thubmnail {{ $row->nama_tryout }}" loading="lazy" />
                                     </a>
                                     <a href="{{ route('mainweb.product-show', ['id' => $row->id]) }}"
                                         class="text-center py-2 px-2 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">
                                         {{ $row->nama_tryout }}
                                     </a>
                                     <h3 class="fw-bold mb-0 mt-3">Rp. {{ number_format($row->harga, 0) }}</h3>
                                     @if ($row->harga_promo != null && $row->harga_promo != 0)
                                         <p class="text-muted">Promo Rp. {{ number_format($row->harga_promo, 0) }}
                                         </p>
                                     @endif

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

                                         {{--  <li class="h6 text-muted mb-0">
                                             <span class="icon h5 me-2">
                                                 <i class="uil uil-check-circle align-middle"></i>
                                             </span>
                                             Akses Bagikan Referal
                                         </li>  --}}
                                         <li class="h6 text-muted mb-0">
                                             <span class="icon h5 me-2">
                                                 <i class="uil uil-check-circle align-middle"></i>
                                             </span>
                                             Masa Aktif {{ $row->masa_aktif }} Hari
                                         </li>
                                     </ul>

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
                 @endif
             </div><!--end row-->
         </div><!--end container-->
     </section><!--end section-->
 @endsection
