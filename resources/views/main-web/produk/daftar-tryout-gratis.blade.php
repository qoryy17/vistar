 @extends('main-web.layout.main')
 @section('title', $title)
 @section('content')
     <section class="section" style="margin-top: 50px;">
         <div class="container">
             <div class="row justify-content-center">
                 <div class="col-12 text-center">
                     <div class="section-title mb-4 pb-2">
                         <h1 class="fs-4 title mb-4">"Jajal Kemampuanmu Tanpa Biaya, Coba Tryout Gratis Sekarang!"</h1>
                         <p class="text-muted para-desc mb-0 mx-auto">Buktikan kemampuanmu dalam tryout gratis dan lihat
                             sejauh mana persiapanmu. Bersama <span class="text-primary fw-bold">Vi Star Indonesia</span>,
                             wujudkan mimpimu menuju kesuksesan lulus CPNS, PPPK, dan Kedinasan!
                         </p>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->

             @if (session()->has('successMessage'))
                 <div class="row mb-4">
                     <div class="col-lg-12">
                         <div class="alert bg-soft-primary fw-medium" role="alert"> <i
                                 class="uil uil-info-circle fs-5 align-middle me-1"></i>
                             {{ session('successMessage') }}
                         </div>
                     </div>
                 </div>
             @elseif (session()->has('errorMessage'))
                 <div class="row mb-4">
                     <div class="col-lg-12">
                         <div class="alert bg-soft-danger fw-medium" role="alert"> <i
                                 class="uil uil-info-circle fs-5 align-middle me-1"></i>
                             {{ session('errorMessage') }}
                         </div>
                     </div>
                 </div>
             @endif
             <div class="row">
                 <div class="col-md-4">
                     <img src="{{ url('resources/images/model2.png') }}" class="rounded img-fluid mx-auto d-block"
                         alt="Banner Model 2 {{ config('app.name') }}" title="Banner Model 2 {{ config('app.name') }}"
                         loading="eager">
                 </div>
                 <div class="col-md-8">
                     <form action="{{ route('orders.simpan-gratis') }}" method="POST" enctype="multipart/form-data"
                         id="formRegisterFreeTryout">
                         @csrf
                         @method('POST')
                         <div class="form-group">
                             <label for="namaLengkap" class="form-label">
                                 Nama Lengkap <span class="text-danger">*</span>
                             </label>
                             <div class="form-icon position-relative">
                                 <i data-feather="user" class="fea icon-sm icons"></i>
                                 <input name="namaLengkap" id="namaLengkap" type="text" class="form-control ps-5"
                                     autocomplete="off" placeholder="Nama Lengkap Anda..." required
                                     value="{{ $customer->nama_lengkap }}" disabled>
                             </div>
                             @error('namaLengkap')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="email" class="form-label">
                                 Email <span class="text-danger">*</span>
                             </label>
                             <div class="form-icon position-relative">
                                 <i data-feather="mail" class="fea icon-sm icons"></i>
                                 <input name="email" id="email" type="email" class="form-control ps-5"
                                     autocomplete="off" placeholder="Email Anda..." required
                                     value="{{ Auth::user()->email }}" disabled>
                             </div>
                             @error('namaLengkap')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="kontak" class="form-label">
                                 No. Kontak <span class="text-danger">*</span>
                             </label>
                             <div class="form-icon position-relative">
                                 <i data-feather="phone" class="fea icon-sm icons"></i>
                                 <input name="kontak" id="kontak" type="number" class="form-control ps-5"
                                     autocomplete="off" placeholder="No. Kontak Anda..." required
                                     value="{{ $customer->kontak }}" disabled>
                             </div>
                             @error('namaLengkap')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="buktiShare" class="form-label">
                                 Unggah Bukti Share Produk Kami <span class="text-danger">*</span>
                             </label>
                             <input type="file" name="buktiShare" required class="form-control"
                                 accept=".jpg,.jpeg,.png" />
                             @error('buktiShare')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="buktiFollow" class="form-label">
                                 Unggah Bukti Follow Akun Instagram Kami <span class="text-danger">*</span>
                             </label>
                             <input type="file" name="buktiFollow" required class="form-control"
                                 accept=".jpg,.jpeg,.png" />
                             @error('buktiFollow')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="informasi" class="form-label">
                                 Sumber Informasi Yang Anda Dapat ? <span class="text-danger">*</span>
                             </label>
                             <select name="informasi" id="informasi" class="form-control" required>
                                 <option value="">Pilih</option>
                                 <option value="Website" {{ old('informasi') === 'Website' ? 'selected' : '' }}>Website
                                 </option>
                                 <option value="Facebook" {{ old('informasi') === 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                 <option value="Instagram" {{ old('informasi') === 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                 <option value="Keluarga/Teman/Kerabat" {{ old('informasi') === 'Keluarga/Teman/Kerabat' ? 'selected' : '' }}>Keluarga/Teman/Kerabat</option>
                                 <option value="Lainnya" {{ old('informasi') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                             </select>
                             @error('informasi')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="form-group mt-2">
                             <label for="alasan" class="form-label">
                                 Alasan Anda Ikut Tryout Gratis ? <span class="text-danger">*</span>
                             </label>
                             <textarea name="alasan" id="alasan" class="form-control"
                                 placeholder="Alasan Anda Ikut Tryout Gratis Dari Kami..." required>{{ old('alasan') }}</textarea>
                             @error('informasi')
                                 <small class="text-danger mt-3">* {{ $message }}</small>
                             @enderror
                         </div>
                         <div class="row mt-2">
                             <div class="col-md-6">
                                 <div class="d-grid">
                                     <button type="submit" class="btn btn-soft-primary btn-pills mt-2">
                                         <span class="mdi mdi-checkbox-marked-circle"></span>
                                         Daftar Sekarang
                                     </button>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="d-grid">
                                     <button type="reset" class="btn btn-soft-warning btn-pills mt-2">
                                         <span class="mdi mdi-refresh"></span>
                                         Reset Formulir
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>

             </div><!--end row-->
         </div><!--end container-->

         <div class="container mt-100 mt-60">
             <div class="rounded bg-primary bg-gradient p-lg-5 p-4">
                 <div class="row align-items-end">
                     <div class="col-md-8">
                         <div class="section-title text-md-start text-center">
                             <p class="title mb-3 text-white title-dark">
                                 Beli Paket Tryout Berbayar
                             </p>
                             <p class="text-white-50 mb-0">
                                 Ayo tunggu apalagi, dapatkan fitur premium hanya dengan sekali beli untuk paket tryout
                                 CPNS, PPPK, Kedinasan.
                             </p>
                         </div>
                     </div><!--end col-->

                     <div class="col-md-4 mt-4 mt-sm-0">
                         <div class="text-md-end text-center">
                             <a href="{{ route('mainweb.product') }}" class="btn btn-light btn-pills">
                                 Pesan Sekarang
                             </a>
                         </div>
                     </div><!--end col-->
                 </div><!--end row-->
             </div>
         </div>
         <!-- End -->
     </section><!--end section-->
 @endsection
 @section('scripts')
     <script>
         $('#formRegisterFreeTryout').on('submit', function() {
             const userId = "{{ Auth::id() }}";
             const fullName = $('[name="namaLengkap"]').val();
             const email = $('[name="email"]').val();
             const phone = $('[name="kontak"]').val();

             analyticsLeadEvent({
                 userData: {
                     id: userId,
                     full_name: fullName,
                     email: email,
                     phone: phone,
                 },
                 totalPrice: 0,
                 currency: "IDR",
             })
         });
     </script>
 @endsection
