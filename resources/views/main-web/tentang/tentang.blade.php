 @extends('main-web.layout.main')
 @section('title', $title)
 @section('content')
     <!-- About Start -->
     <section class="section" class="bg-half-170 bg-light d-table w-100 mt-100">
         <div class="container">
             <div class="row align-items-center">
                 <div class="col-lg-5 col-md-5 mt-4 pt-2 mt-sm-0 pt-sm-0 wow animate__animated animate__fadeInLeft"
                     data-wow-delay=".1s">
                     <div class="position-relative">
                         <img src="{{ asset('storage/' . $web->logo) }}" class="rounded img-fluid mx-auto d-block"
                             alt="">

                     </div>
                 </div><!--end col-->

                 <div class="col-lg-7 col-md-7 mt-4 pt-2 mt-sm-0 pt-sm-0 wow animate__animated animate__fadeInRight"
                     data-wow-delay=".1s">
                     <div class="section-title ms-lg-4">
                         <h4 class="title mb-4 fw-bold" style="text-transform: uppercase; color: #0075B8;">
                             {{ $web->nama_bisnis }}</h4>
                         <p class="text-muted" style="text-align: justify">
                             Selamat datang di {{ $web->nama_bisnis }}, Pusat Kegiatan Akademik yang menghadirkan inovasi
                             dan keunggulan di
                             bidang ICT dan Science. Kami bangga menjadi bagian dari perjalanan pendidikan Anda, dengan
                             fokus untuk mencetak generasi yang siap bersaing di era digital.
                         </p>
                         <p class="text-muted" style="text-align: justify">
                             {{ $web->nama_bisnis }} merupakan Pusat Kegiatan Akademik Bidang ICT dan Science Terbaik #1 di
                             Indonesia,
                             mengedepankan VI (6 angka Romawi) dan Star (Bintang dalam bahasa Inggris), yang melambangkan “6
                             Bintang” di bidang Si: KompetenSi, KompetiSi, LiteraSi, OkupaSi, PrestaSi, dan SertifikaSi.
                             Dengan pendekatan ini, kami berkomitmen untuk menciptakan lingkungan belajar yang kompetitif,
                             inovatif, dan berorientasi pada prestasi.
                         </p>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->
         </div><!--end container-->

         <div class="container mt-100 mt-60">
             <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                 <div class="col-12 text-center">
                     <div class="section-title mb-4 pb-2">
                         <h4 class="title mb-4">Dimana Lokasi Kami ?</h4>
                         <p class="text-muted mx-auto">
                             Berkantor pusat di Sumatera Utara, kami bangga menjadi bagian dari komunitas lokal sambil
                             menjangkau seluruh nusantara dengan layanan kami. Selain itu, kami menyediakan fitur lengkap
                             untuk pembahasan ujian tryout berbayar yang dirancang untuk memberikan pengalaman belajar
                             terbaik, membantu Anda memahami setiap materi dengan lebih baik dan meningkatkan peluang
                             keberhasilan Anda.
                         </p>

                         <p class="text-muted">
                             Bersama {{ $web->nama_bisnis }}, persiapan ujian Anda lebih terarah, lebih efektif, dan tentu
                             saja
                             lebih menyenangkan. Bergabunglah dengan ribuan peserta lainnya dan wujudkan impian Anda menjadi
                             kenyataan!
                         </p>
                     </div>
                 </div><!--end col-->
             </div><!--end row-->
             <div class="row mt-50">
                 <div class="col-lg-6 col-md-6 wow animate__animated animate__fadeInLeft" data-wow-delay=".1s">
                     <div class="card border-0 text-center features feature-primary feature-clean p-2">
                         <div class="icons text-center mx-auto">
                             <i class="uil uil-phone rounded h3 mb-0"></i>
                         </div>
                         <div class="content mt-4">
                             <h5 class="fw-bold">Kontak</h5>
                             <p class="text-muted">Jangan ragu untuk
                                 menghubungi kami, kami selalu di sini untuk Anda</p>
                             <a onclick="analyticsContactEvent({contact_type: 'phone', value: '{{ $web->kontak }}'})"
                                 href="tel:{{ $web->kontak }}" class="read-more">
                                 {{ $web->kontak }}
                             </a>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-6 wow animate__animated animate__fadeInRight" data-wow-delay=".1s">
                     <div class="card border-0 text-center features feature-primary feature-clean p-2">
                         <div class="icons text-center mx-auto">
                             <i class="uil uil-envelope rounded h3 mb-0"></i>
                         </div>
                         <div class="content mt-4">
                             <h5 class="fw-bold">Email</h5>
                             <p class="text-muted">Butuh solusi? Kirimkan email Anda dan kami akan segera merespon</p>
                             <a onclick="analyticsContactEvent({contact_type: 'email', value: '{{ $web->email }}'})"
                                 href="mailto:{{ $web->email }}" class="read-more">
                                 {{ $web->email }}
                             </a>
                         </div>
                     </div>
                 </div>
             </div>
         </div><!--end container-->
     </section><!--end section-->
     <!-- About End -->
 @endsection
