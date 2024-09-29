@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Start Terms of Service -->
    <section class="mt-5 pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow rounded border-0">
                        <div class="card-body">
                            <h1 class="fs-2 text-primary fw-bold text-center mb-3">
                                Syarat & Ketentuan {{ $web->nama_bisnis }}
                            </h1>
                            <div>
                                <h2 class="fs-5">1. Ketentuan</h2>
                                <p>
                                    Dengan mengakses situs web <a title="{{ $web->nama_bisnis }}"
                                        href="{{ route('mainweb.index') }}"
                                        style="color: black; text-decoration: none;">{{ route('mainweb.index') }}</a>,
                                    Anda
                                    setuju untuk terikat oleh persyaratan layanan ini, semua undang-undang dan peraturan
                                    yang berlaku, dan setuju bahwa Anda bertanggung jawab untuk mematuhi semua hukum
                                    setempat yang berlaku. Jika Anda tidak setuju dengan ketentuan ini, Anda dilarang
                                    menggunakan atau mengakses situs ini. Materi yang terkandung dalam situs web ini
                                    dilindungi oleh undang-undang hak cipta dan merek dagang yang berlaku.
                                </p>

                                <h2 class="fs-5">2. Gunakan Lisensi</h2>
                                <ol>
                                    <li>
                                        Izin diberikan untuk mengunduh sementara satu salinan materi (informasi atau
                                        perangkat lunak) di situs web {{ $web->nama_bisnis }} hanya untuk melihat
                                        sementara,
                                        non-komersial. Ini adalah pemberian lisensi, bukan transfer judul, dan di bawah
                                        lisensi ini Anda tidak boleh:
                                        <ol>
                                            <li>memodifikasi atau menyalin materi;</li>
                                            <li>menggunakan materi untuk tujuan komersial, atau untuk tampilan publik
                                                (komersial atau non-komersial);</li>
                                            <li>berupaya mendekompilasi atau merekayasa balik perangkat lunak apa pun
                                                yang
                                                terdapat di situs web {{ $web->nama_bisnis }};</li>
                                            <li>menghapus hak cipta atau notasi kepemilikan lainnya dari materi; atau
                                            </li>
                                            <li>mentransfer materi ke orang lain atau "mirror" materi di server lain.
                                            </li>
                                        </ol>
                                    </li>
                                    <li>
                                        Lisensi ini akan berakhir secara otomatis jika Anda melanggar salah satu dari
                                        pembatasan ini dan dapat dihentikan oleh {{ $web->nama_bisnis }} kapan
                                        saja. Setelah
                                        mengakhiri penayangan materi ini atau setelah penghentian lisensi ini, Anda
                                        harus
                                        memusnahkan semua materi yang diunduh dalam kepemilikan Anda baik dalam format
                                        elektronik atau cetak.
                                    </li>
                                </ol>

                                <h2 class="fs-5">3. Penafian</h2>
                                <ol>
                                    <li>
                                        Bahan-bahan di situs web {{ $web->nama_bisnis }} disediakan atas dasar 'apa
                                        adanya'. {{ $web->nama_bisnis }} tidak membuat jaminan, tersurat maupun
                                        tersirat,
                                        dan dengan ini menafikan dan
                                        meniadakan semua jaminan lainnya termasuk, tanpa batasan, jaminan tersirat atau
                                        ketentuan yang dapat diperjualbelikan, kesesuaian untuk tujuan tertentu, atau
                                        tidak
                                        melanggar hak kekayaan intelektual atau pelanggaran hak lainnya.
                                    </li>
                                    <li>
                                        Lebih lanjut, {{ $web->nama_bisnis }} tidak menjamin atau membuat pernyataan apa
                                        pun
                                        terkait
                                        keakuratan, kemungkinan hasil, atau keandalan penggunaan materi di situs webnya
                                        atau
                                        yang terkait dengan materi tersebut atau di situs mana pun yang terhubung ke
                                        situs
                                        ini.
                                    </li>
                                </ol>

                                <h2 class="fs-5">4. Keterbatasan</h2>
                                <p>Dalam keadaan apa pun, {{ $web->nama_bisnis }} atau pemasoknya tidak bertanggung
                                    jawab
                                    atas segala
                                    kerusakan (termasuk, tanpa batasan, kerusakan karena kehilangan data atau laba, atau
                                    karena gangguan bisnis) yang timbul dari penggunaan atau ketidakmampuan untuk
                                    menggunakan materi di situs web {{ $web->nama_bisnis }}, bahkan jika
                                    {{ $web->nama_bisnis }} atau perwakilan
                                    resmi {{ $web->nama_bisnis }} telah diberi tahu secara lisan atau tertulis tentang
                                    kemungkinan
                                    kerusakan tersebut. Karena beberapa yurisdiksi tidak mengizinkan pembatasan pada
                                    jaminan tersirat, atau batasan tanggung jawab atas kerusakan konsekuensial atau
                                    insidental, batasan ini mungkin tidak berlaku untuk Anda.
                                </p>

                                <h2 class="fs-5">5. Akurasi bahan</h2>
                                <p>
                                    Materi yang muncul di situs web {{ $web->nama_bisnis }} dapat mencakup kesalahan
                                    teknis, tipografi atau fotografi. {{ $web->nama_bisnis }} tidak menjamin bahwa
                                    semua
                                    materi di situs webnya
                                    akurat, lengkap, atau terkini. {{ $web->nama_bisnis }} dapat membuat perubahan
                                    pada materi yang terkandung di situs webnya kapan saja tanpa pemberitahuan. Namun
                                    {{ $web->nama_bisnis }} tidak membuat komitmen untuk memperbarui materi.
                                </p>

                                <h2 class="fs-5">6. Tautan</h2>
                                <p>{{ $web->nama_bisnis }} belum meninjau semua situs yang terhubung ke situs webnya
                                    dan
                                    tidak
                                    bertanggung jawab atas isi dari situs yang terhubung tersebut. Dimasukkannya tautan
                                    apa pun tidak menyiratkan dukungan oleh {{ $web->nama_bisnis }} dari situs
                                    tersebut. Penggunaan
                                    situs web yang ditautkan tersebut merupakan risiko pengguna sendiri.</p>

                                <h2 class="fs-5">7. Modifikasi</h2>
                                <p>
                                    {{ $web->nama_bisnis }} dapat merevisi ketentuan layanan ini untuk situs webnya
                                    kapan
                                    saja tanpa
                                    pemberitahuan. Dengan menggunakan situs web ini, Anda setuju untuk terikat oleh
                                    versi terbaru dari ketentuan layanan ini.
                                </p>

                                <h2 class="fs-5">8. Hukum Yang Mengatur</h2>
                                <p>
                                    Syarat dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Indonesia
                                    yang
                                    berlaku dan Anda tidak dapat ditarik kembali tunduk kepada yurisdiksi eksklusif
                                    pengadilan di Negara atau lokasi itu.
                                </p>
                            </div>

                            <a href="javascript:window.print()" class="btn btn-pills btn-soft-primary d-print-none">
                                Cetak
                            </a>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End Privacy -->
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
                'name' => 'Syarat & Ketentuan',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
