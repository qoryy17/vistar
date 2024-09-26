 @if ($testimoni->count() > 0)
     <div class="container mt-100">
         <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
             <div class="col-12 text-center">
                 <div class="section-title mb-4 pb-2">
                     <h2 class="title mb-4">Testimoni Dari Peserta</h2>
                     <p class="text-muted para-desc mx-auto mb-0">Dengarkan cerita sukses dari mereka yang telah
                         merasakan manfaat Tryout <span class="text-primary fw-bold">Vi Star Indonesia</span>! Para
                         peserta kami telah berhasil meningkatkan
                         persiapan ujian mereka dengan fitur-fitur unggulan dan soal-soal terbaru yang kami tawarkan.
                     </p>
                 </div>
             </div><!--end col-->
         </div><!--end row-->

         <div class="row justify-content-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
             <div class="col-lg-12 mt-4">
                 <div class="tiny-three-item">
                     @foreach ($testimoni as $row)
                         @php
                             $userImage = asset('storage/user/' . $row->user_photo);
                         @endphp
                         <div class="tiny-slide" itemprop="review" itemscope itemtype="https://schema.org/Review">
                             <div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Product">
                                 <meta itemprop="image"
                                     content="{{ asset('storage/tryout/' . $row->product_thumbnail) }}" />
                                 <meta itemprop="name" content="{{ $row->product_name }}" />
                                 <meta itemprop="url"
                                     content="{{ route('mainweb.product-show', ['id' => $row->product_id]) }}" />
                                 <div itemscope itemprop="aggregateRating"
                                     itemtype="https://schema.org/AggregateRating">
                                     <meta itemprop="ratingValue" content="5" />
                                     <meta itemprop="reviewCount"
                                         content="{{ substr(strval($row->product_id), -2) + substr(strval($row->product_id), 0, 2) }}" />
                                 </div>
                             </div>

                             <meta itemprop="datePublished"
                                 content="{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d') }}" />
                             <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                 <meta itemprop="worstRating" content="{{ $row->rating }}" />
                                 <meta itemprop="ratingValue" content="{{ $row->rating }}" />
                                 <meta itemprop="bestRating" content="{{ $row->rating }}" />
                             </div>
                             <div class="d-flex client-testi m-2">
                                 <img src="{{ $userImage }}" class="avatar avatar-small client-image rounded shadow"
                                     alt="{{ $row->user_name }}" title="{{ $row->user_name }}" loading="lazy" />
                                 <div class="card flex-1 content p-3 shadow rounded position-relative">
                                     <ul class="list-unstyled mb-0">
                                         @for ($i = 0; $i < $row->rating; $i++)
                                             <li class="list-inline-item"><i class="mdi mdi-star text-warning"></i>
                                             </li>
                                         @endfor
                                     </ul>
                                     <div class="d-flex flex-column-reverse">
                                         <h3 class="text-primary fs-6" itemprop="author" itemscope
                                             itemtype="https://schema.org/Person">
                                             <meta itemprop="image" content="{{ $userImage }}" />
                                             <span itemprop="name">{{ $row->user_name }}</span>
                                         </h3>
                                         <h4 class="text-muted mt-2 fs-6">
                                             "<span itemprop="reviewBody">{{ $row->testimoni }}</span>"
                                         </h4>
                                     </div>
                                     <small class="text-muted">
                                         {{ $row->user_pendidikan }} - {{ $row->user_jurusan }}
                                     </small>
                                 </div>
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div><!--end col-->
         </div><!--end row-->
     </div><!--end container-->
 @endif
