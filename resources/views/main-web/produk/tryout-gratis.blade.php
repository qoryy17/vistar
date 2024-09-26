 @extends('main-web.layout.main')
 @section('title', $title)
 @section('content')
     <section class="section" style="margin-top: 50px;">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-12 text-center">
                     <div class="section-title mb-4 pb-2">
                         <h4 class="title mb-4">{{ $title }}</h4>
                         <p class="text-muted para-desc mb-0 mx-auto">
                             Temukan produk gratis yang bisa anda coba sekali !
                             <span class="text-primary fw-bold">Vistar Indonesia</span>
                             memberikan solusi terbaik untuk persiapan Anda.
                         </p>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->

             <!-- Filter Pencarian Produk Tryout -->
             <form action="{{ route('mainweb.free-product') }}" method="GET">
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
                                 <a href="{{ route('mainweb.free-product') }}" class="btn btn-pills btn-warning btn-block ">
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
                 @endif
                 @php
                     $no = 1;
                 @endphp
                 @foreach ($products as $row)
                     <div class="col-lg-4 col-md-6 mt-4 pt-2">
                         <div class="card pricing pricing-primary business-rate border-0 p-4 rounded-md shadow">
                             <div class="card-body p-0">
                                 <span
                                     class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-0 text-primary rounded-lg">{{ $row->nama_tryout }}</span>
                                 <h2 class="fw-bold mb-0 mt-3">Gratis</h2>
                                 <p class="text-muted">{{ $row->keterangan }}</p>
                                 <p class="text-muted">Fitur yang anda dapatkan dalam paket ini</p>

                                 <ul class="list-unstyled pt-3 border-top">
                                     <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                             <i class="uil uil-check-circle align-middle"></i></span>Gratis Ujian 1x
                                     </li>
                                     <li class="h6 text-muted mb-0"><span class="icon h5 me-2">
                                             <i class="uil uil-check-circle align-middle"></i></span>Hasil Ujian
                                     </li>
                                 </ul>

                                 <div class="mt-4">
                                     <div class="d-grid">
                                         <a onclick="submitForm{{ $no }}()" href="javascript:void(0)"
                                             class="btn btn-pills btn-primary">Pilih Paket</a>
                                         <form id="keranjangForm{{ $no }}"
                                             action="{{ route('mainweb.pesan-tryout-gratis', ['idProdukTryout' => Crypt::encrypt($row->id)]) }}"
                                             method="POST">
                                             @csrf
                                             @method('POST')
                                         </form>
                                         <script>
                                             function submitForm{{ $no }}() {
                                                 document.getElementById('keranjangForm{{ $no }}').submit();
                                             }
                                         </script>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div><!--end col-->
                     @php
                         $no++;
                     @endphp
                 @endforeach
                 <div class="mt-5 table-responsive">
                     {{ $products->appends(request()->query())->links() }}
                 </div>
             </div><!--end row-->
         </div><!--end container-->
         <!-- Price End -->
     </section><!--end section-->
 @endsection
