 <!-- Testimoni Peserta -->
 <div class="container mt-50 mt-60">
     <div class="row justify-content-center">
         <div class="col-12 text-center">
             <div class="section-title mb-4 pb-2">
                 <h4 class="title mb-4">Testimoni Dari Peserta</h4>
                 <p class="text-muted para-desc mx-auto mb-0">Dengarkan cerita sukses dari mereka yang telah
                     merasakan manfaat Tryout <span class="text-primary fw-bold">Vi Star Indonesia</span>! Para
                     peserta kami telah berhasil meningkatkan
                     persiapan ujian mereka dengan fitur-fitur unggulan dan soal-soal terbaru yang kami tawarkan.
                 </p>
             </div>
         </div><!--end col-->
     </div><!--end row-->

     <div class="row justify-content-center">
         <div class="col-lg-12 mt-4">
             <div class="tiny-three-item">
                 @foreach ($testimoni->get() as $testimoniPeserta)
                     <div class="tiny-slide">
                         <div class="d-flex client-testi m-2">
                             <img src="{{ asset('storage/user/' . $testimoniPeserta->foto) }}"
                                 class="avatar avatar-small client-image rounded shadow" alt="">
                             <div class="card flex-1 content p-3 shadow rounded position-relative">
                                 <ul class="list-unstyled mb-0">
                                     @for ($i = 0; $i < $testimoniPeserta->rating; $i++)
                                         <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i>
                                         </li>
                                     @endfor
                                 </ul>
                                 <p class="text-muted mt-2">" {{ $testimoniPeserta->testimoni }} "</p>
                                 <h6 class="text-primary">
                                     {{ $testimoniPeserta->nama_lengkap }}
                                 </h6>
                                 <small class="text-muted">
                                     {{ $testimoniPeserta->pendidikan }} - {{ $testimoniPeserta->jurusan }}
                                 </small>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>
         </div><!--end col-->
     </div><!--end row-->
 </div><!--end container-->
 <!-- End Testimoni Perserta -->
