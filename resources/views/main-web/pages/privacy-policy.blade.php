@extends('main-web.layout.main')
@section('title', $title)
@section('content')
    <!-- Start Kebijakan Privasi -->
    <section class="mt-5 pt-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow rounded border-0">
                        <div class="card-body">
                            <h1 class="fs-2 text-primary fw-bold text-center mb-3">
                                Kebijakan Privasi {{ $web->nama_bisnis }}
                            </h1>
                            <p class="text-muted" style="text-align: justify">
                                Kerahasian data pengguna merupakan hal yang amat penting bagi . Kami memegang teguh komitmen
                                untuk melindungi dan menghormati privasi pelanggan Kami sebelum, selama dan sesudah
                                menggunakan aplikasi serta situs web yang dikelola di bawah supervisi
                                <span class="text-primary">{{ $web->nama_bisnis }}</span>
                            </p>
                            <p class="text-muted" style="text-align: justify">
                                Kebijakan Privasi ini merumuskan fondasi dasar tentang bagaimana Kami menggunakan data
                                Personal yang Kami peroleh dan/atau yang Anda berikan. Kebijakan Privasi berikut mengikat
                                atas segenap pelanggan Kami. Diharapkan untuk membaca Kebijakan Privasi ini dengan cermat
                                sehingga dapat difahami mengenai kebijakan dan cara Kami memanfaatkan informasi yang Anda
                                berikan.
                            </p>

                            <h2 class="fs-4 card-title">Kebijakan Privasi ini mencakup beberapa hal, yaitu :</h2>
                            <ul class="list-unstyled text-muted">
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Informasi Personal yang berhak Kami kumpulkan
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Pemanfaatan informasi Personal
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Publikasi informasi Personal
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Arsip informasi Personal
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Hak pengguna
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Kebijakan cookies
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Pengakuan dan persetujuan
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Konten promosi
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Amandemen kebijakan privasi Kami
                                </li>
                                <li>
                                    <i data-feather="arrow-right" class="fea icon-sm me-2"></i>
                                    Kontak informasi dan keluhan
                                </li>
                            </ul>

                            <p class="text-muted" style="text-align: justify">
                                Dengan mengunjungi dan/atau mendaftar Akun pada Platform Kami, Anda menerima dan menyetujui
                                pendekatan dan cara-cara yang digambarkan dalam Kebijakan Privasi ini
                            </p>

                            <h3 class="fs-5 card-title">A. Informasi Personal yang berhak Kami kumpulkan</h3>
                            <p class="text-muted" style="text-align: justify">
                                Kami berhak mengumpulkan informasi Personal seperti :
                            <ol class="text-muted" style="text-align: justify;">
                                <li>
                                    <strong>Informasi yang diberikan pengguna.</strong>
                                    Anda bisa memberikan informasi dengan cara mengisi formulir daring pada Platform Kami
                                    ataupun dengan berinteraksi melalui kontak telepon, surat elektronik, dan berbagai
                                    saluran komunikasi lainnya. Informasi tersebut meliputi informasi yang Anda berikan pada
                                    saat mendaftar di Platform Kami, berlangganan jasa Kami, ikut serta dalam diskusi daring
                                    maupun interaksi di media sosial lain pada Platform Kami, mengikuti olimpiade, promosi,
                                    atau survei, serta saat Anda melaporkan masalah dengan Platform Kami. Informasi yang
                                    Anda berikan bisa saja meliputi nama, alamat domisili, alamat surat elektronik, nomor
                                    kontak, informasi finansial seperti kartu kredit, bio Personal, foto, dan data lainnya
                                    yang berhubungan. Kami berhak meminta Anda untuk melakukan verifikasi atas informasi
                                    yang diberikan, guna memastikan kebenaran dari informasi yang ada.
                                </li>
                                <li>
                                    <strong>Informasi yang Kami kumpulkan.</strong>
                                    Dari semua kunjungan Anda ke Platform Kami, Kami berhak mengumpulkan informasi tersebut
                                    di bawah ini secara langsung:
                                    <ul>
                                        <li>Informasi teknis, seperti alamat Protokol Internet (IP address) sebagai
                                            identitas komputer Anda saat berselancar di dunia maya, informasi log in yang
                                            Anda lakukan, perambah dan versi yang digunakan, zona waktu yang digunakan di
                                            komputer Anda, serta sistem operasi atau platform yang digunakan;
                                        </li>
                                        <li>
                                            Informasi mengenai kunjungan Anda, yang di dalamnya mengandung <i>Uniform
                                                Resource
                                                Locators</i> atau URL yang diakses menuju ke, melalui dengan, dan berasal
                                            dari
                                            Platform Kami (termasuk tanggal dan waktu kunjungan); produk yang Anda buka atau
                                            telusuri; jadwal respon halaman, kendala pengunduhan, durasi kunjungan di
                                            halaman tertentu, informasi interaksi pada antarmuka (seperti pengguliran, klik,
                                            maupun pergerakan kursor), metode yang digunakan untuk keluar dari situs, serta
                                            nomor kontak yang digunakan untuk menghubungi layanan konsumen Kami.
                                        </li>
                                        <li>
                                            Data nilai yang Anda peroleh, termasuk namun tidak terbatas pada hasil tes Anda
                                            yang dihasilkan melalui Platform, serta data yang bersifat akademis lainnya.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Informasi yang diperoleh dari sumbel lain.</strong>Kami berhak memperoleh
                                    informasi jika Anda membuka situs web lain yang Kami kelola atau jasa layanan lain yang
                                    Kami sediakan. Kami juga membuka bekerja sama ke pihak ketiga (termasuk, namun tidak
                                    terbatas pada contohnya, mitra usaha, sub-kontraktor dalam penanganan teknis, jasa
                                    pembayaran elektronik, jaringan jasa periklanan, penyedia analisa, penyedia mesin
                                    pencari informasi, dan lainnya) dan dapat memperoleh informasi dari mereka. Kami akan
                                    mengambil sikap dalam batas kewajaran guna melakukan verifikasi atas informasi yang Kami
                                    dapatkan dari sumber lain sesuai dengan Peraturan Perundang-Undangan yang diterapkan.
                                </li>
                            </ol>
                            </p>

                            <h3 class="fs-5 card-title">B. Pemanfaatan informasi Personal</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Cara-cara Kami memanfaatkan Informasi Personal adalah sebagai berikut:
                            <ol class="text-muted" style="text-align: justify;">
                                <li>
                                    Informasi yang Anda berikan. Kami akan memanfaatkan informasi ini:
                                    <ul>
                                        <li>Demi menjalankan kewajiban Kami untuk memberikan informasi, produk, dan jasa
                                            kepada Anda
                                        </li>
                                        <li>
                                            Demi menyediakan informasi terkait produk atau jasa lain yang mungkin cocok
                                            dengan minat anda; atau mengizinkan pihak ketiga untuk mengabarkan Anda,
                                            informasi mengenai produk ataupun jasa yang Kami anggap relevan dengan kebutuhan
                                            Anda. Jika Anda adalah pelanggan setia Kami, Kami dapat menghubungi Anda secara
                                            daring atau melalui saluran komunikasi lain dengan informasi tentang produk atau
                                            jasa Kami. Jika Anda adalah pelanggan yang baru bergabung, dan di mana Kami
                                            bekerjasama dengak pihak ketiga untuk penggunaan Informasi Personal Anda, Kami
                                            (ataupun mereka) dapat menawarkan kepada Anda secara daring hanya jika dalam
                                            persetujuan dari Anda.
                                        </li>
                                        <li>
                                            Untuk menginformasikan kepada Anda perihal pemutakhiran pada layanan Kami;
                                        </li>
                                        <li>
                                            Untuk memastikan bahwa materi yang Kami sajikan pada Platform Kami sudah sesuai
                                            dengan kepuasan Anda
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    Informasi yang Kami peroleh dari sumber lain. Kami berhak menggabungkan informasi yang
                                    Kami dapat baik dari sumber lain maupun dari informasi yang Anda berikan serta informasi
                                    yang Kami himpun. Kami dapat memanfaatkan informasi ini maupun informasi gabungan untuk
                                    tujuan yang diatur di atas.
                                </li>
                                <li>
                                    Informasi yang Kami kumpulkan. Kami akan memanfaatkan informasi ini:
                                    <ul>
                                        <li>
                                            Untuk mengolah Platform dan operasi internal Kami, termasuk pencarian akar
                                            masalah (troubleshooting), menganalisis data, menguji, meneliti, serta masalah
                                            statistik dan survei lainnya;
                                        </li>
                                        <li>
                                            Untuk memperbaiki Platform Kami agar konten diharapkan dapat disajikan dengan
                                            cara yang paling sesuai untuk Anda;
                                        </li>
                                        <li>
                                            Untuk memungkinkan Anda ikut serta dalam fitur interaktif yang Kami sediakan,
                                            kapan saja ketika Anda menginginkannya;
                                        </li>
                                        <li>
                                            Sebagai bagian dari upaya dalam memastikan keselamatan dan keamanan Platform
                                            Kami;
                                        </li>
                                        <li>
                                            Untuk mengukur dan memahami efektivitas promo yang Kami lakukan terhadap Anda
                                            dan pihak lainnya, serta penyajian iklan produk atau jasa yang relevan untuk
                                            Anda;
                                        </li>
                                        <li>
                                            Untuk memberi saran dan arahan kepada Anda mengenai produk atau jasa yang
                                            mungkin menarik minat Anda.
                                        </li>
                                    </ul>
                                </li>
                            </ol>
                            </p>

                            <h3 class="fs-5 card-title">C. Publikasi informasi Personal</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Kami berhak membagi atau mempublikasi Data Personal bersama anggota kelompok bisnis Kami,
                                yang melingkupi cabang dan anak perusahaan, serta perusahaan induk utama maupun anak
                                perusahaannya. <br>
                                Kami juga berhak menyebarkan Data Personal kepada pihak ketiga, termasuk :
                            <ul class="text-muted" style="text-align: justify;">
                                <li>
                                    Mitra bisnis, pemasok, dan sub-kontraktor dalam terselenggaranya kontrak yang Kami
                                    jalankan dengan mereka atau Anda.
                                </li>
                                <li>
                                    Jasa layanan iklan dan jaringan promosi yang membutuhkan data untuk memilih segmentasi
                                    dan menawarkan produk atau jasa yang sesuai bagi konsumen. Kami tidak membuka informasi
                                    perihal individu yang dapat dikenali, adapun Kami bisa menyediakan kepada mereka
                                    informasi rangkuman perihal pengguna (seperti informasi bahwa 457 wanita berumur di atas
                                    18 tahun telah mengunjungi tautan iklan mereka di waktu tertentu). Kami juga dapat
                                    memberikan informasi rangkuman guna membantu penerbitan iklan dalam menjangkau
                                    segmentasi berbagai target konsumen tertentu (contohnya, perempuan di Medan Baru). Kami
                                    berhak memanfaatkan data Personal yang Kami himpun guna memenuhi keperluan iklan dengan
                                    menampilkan promo mereka kepada segmen konsumen tersebut.
                                </li>
                                <li>
                                    Lembaga yang menyediakan analisis dan mesin pencari yang menunjang pekerjaan Kami guna
                                    memperbaiki dan mengoptimalkan pelayanan Kami.
                                </li>
                            </ul>

                            <br>
                            <span class="text-muted mb-4">Kami dapat mempublikasikan informasi kepada pihak ketiga:</span>
                            <ul class="text-muted" style="text-align: justify;">
                                <li>
                                    Dalam keadaan di mana Kami membeli ataupun menjual perusahaan maupun aset, Kami dapat
                                    mempublikasikan data kepada calon penjual serta calon pembeli dari aset ataupun
                                    perusahaan tersebut.
                                </li>
                                <li>
                                    Jika {{ $web->nama_bisnis }} atau aset-aset pokoknya yang terkait di dalamnya diperoleh
                                    oleh pihak ketiga, maka Data Personal yang dimiliki tentang konsumen Kami akan menjadi
                                    salah satu dari aset yang berpindahtangan.
                                </li>
                                <li>
                                    Jika Kami berada di bawah tanggung jawab untuk mempublikasikan atau berbagi data guna
                                    mematuhi aturan hukum dan perjanjian lain; atau memproteksi hak, aset, maupun keamanan
                                    dari {{ $web->nama_bisnis }}, konsumen Kami, dan lain-lain. Hal ini mencakup
                                    penukaran informasi dengan perusahaan dan lembaga lain demi tujuan perlindungan dari
                                    tindak pidana penipuan dan meminimalisi risiko.
                                </li>
                            </ul>
                            </p>

                            <h3 class="fs-5 card-title">D. Arsip informasi Personal</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Segenap informasi Personal yang Anda serahkan kepada Kami disimpan di server yang aman.
                                Seluruh transaksi keuangan pada Platform akan dienkripsi. Dengan memberikan Informasi
                                Personal Anda pada Platform, Anda menyepakati pengalihan, penyimpanan, serta pengolahan yang
                                dilakukan pada Platform Kami. Kami akan menerapkan berbagai langkah-langkah dalam batas yang
                                sewajarnya jika dibutuhkan guna meyakinkan bahwa Informasi Personal disimpan secara aman
                                juga bersesuaian terhadap Kebijakan Privasi maupun Peraturan atau Hukum yang Berlaku.
                            </p>
                            <p class="text-muted" style="text-align: justify;">
                                Segenap Informasi Personal yang Anda serahkan akan Kami simpan: (a) sepanjang Anda masih
                                terdaftar sebagai pengguna di Platform Kami dan (b) selama anda pernah merasakan jasa
                                Platform Kami; atau (c) bersesuaian atas tujuan semula dari dihimpunnya Informasi Personal
                                tersebut.
                            </p>
                            <p class="text-muted" style="text-align: justify">
                                Dalam keadaan di mana Kami menyediakan (atau Anda menentukan) sebuah kata sandi
                                <i>(password)</i>
                                yang mengizinkan Anda mengakses layanan tertentu pada Platform Kami, Anda sepenuhnya
                                bertanggung jawab untuk mempertahankan kerahasiaan kata sandi Anda. Kami sangat mengharapkan
                                Anda agar tidak menyebarkan kata sandi kepada publik.
                            </p>
                            <p class="text-muted" style="text-align: justify">
                                Mohon kiranya untuk diketahui bahwa pengunggahan informasi melalui internet tidak dapat
                                dipastikan sepenuhnya aman. Meski demikian, Kami tetap akan berusaha dengan sebaik mungkin
                                untuk melindungi Data Konsumen tersebut. Kami tidak dapat menjamin seutuhnya keamanan
                                informasi yang Anda kirimkan melalui Platform Kami; risiko dari segenap transmisi menjadi
                                tanggung jawab pribadi Anda. Sejak Kami menerima Informasi Personal Anda, Kami akan
                                menerapkan prosedur yang ketat dan teknologi keamanan yang mumpuni guna mencegah akses yang
                                ilegal.
                            </p>

                            <h3 class="fs-5 card-title">E. Hak pengguna</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Anda dapat mengajukan permohonan penghapusan Informasi Personal Anda di Platform ataupun
                                menarik kembali persetujuan Anda untuk sebagian atau seluruh pengumpulan, pemanfaatan atau
                                penyingkapan Informasi Personal Anda dengan cara memberikan kepada kami pemberitahuan yang
                                wajar secara tertulis melalui detail kontak yang kami pada bagian terakhir di Kebijakan
                                Privasi ini. Sesuai pada kondisi dan sifat permohonan yang Anda ajukan, Anda harus memahami
                                benar dan mengakui bahwa selepas penarikan persetujuan atau permohonan penghapusan dimaksud,
                                Anda bisa saja tidak lagi memiliki akses ke Platform Kami. Penarikan persetujuan Anda bisa
                                berdampak pada pemberhentian Akun Anda atau hubungan kontrak Anda dengan kami, dengan
                                segenap hak dan kewajiban yang ada sepenuhnya harus dipatuhi. Setelah mendapat pemberitahuan
                                untuk penarikan persetujuan guna pengumpulan, pemanfaatan atau penyingkapan Informasi
                                Personal Anda, Kami akan memberitahukan kepada Anda tentang dampak yang mungkin terjadi dari
                                penarikan tersebut agar Anda dapat mengambil keputusan apakah tetap menarik persetujuan atau
                                membatalkannya.
                            </p>
                            <p class="text-muted" style="text-align: justify;">
                                Anda bisa mengirimkan pengajuan kepada Kami untuk mengoreksi Informasi Personal yang ada di
                                bawah kepemilikan ataupun penguasaan kami, dengan menghubungi kontak di bawah ini.

                                Platform Kami mungkin saja dari waktu ke waktu, memuat tautan menuju dan dari berbagai situs
                                milik jaringan mitra, pemuat iklan, dan afiliasi terkait. Jika Anda mengikuti tautan ke
                                salah satu situs yang dimaksud, mohon diperhatikan bahwa situs-situs tersebut mungkin saja
                                memiliki Kebijakan Privasi yang berbeda dan bahwasanya Kami dalam hal ini tidaklah
                                bertanggung jawab ataupun memiliki kewajiban terhadap kebijakan-kebijakan pihak-pihak
                                tersebut. Mohon diperiksa kebijakan-kebijakan tersebut sebelum Anda memberikan informasi apa
                                pun ke dalam situs-situs tersebut.
                            </p>
                            <h3 class="fs-5 card-title">F. Kebijakan Cookies</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Ketika Anda mengakses Platform, Kami berhak menempatkan sejumlah <i>cookies</i> dalam
                                browser Anda.
                                <i>Cookies</i> adalah sebuah file digital kecil berisi huruf maupun angka yang disimpan pada
                                perambah atau penyimpanan komputer Anda atas persetujuan Anda. Cookies memuat
                                informasi-informasi yang ditransmisikan ke hard disk di komputer Anda.

                                <br>
                                <br>
                                <i>Cookies</i> bisa digunakan untuk tujuan berikut: (1) mengaktifkan fitur tertentu, (2)
                                melakukan
                                analisis, (3) sebagai preferensi Anda; dan (4) membantu promosi iklan berdasarkan perilaku.
                                <i> Cookies-cookies</i> ini hanya akan diterapkan jika Anda menjalankan fungsi-fungsi
                                tertentu
                                ataupun memilih preferensi tertentu, sementara sebagian dari Cookies lain bisa aja akan
                                senantiasa digunakan dengan wajar.
                            </p>
                            <p class="text-muted" style="text-align: justify;">
                                <i>Cookies</i> digunakan untuk alasan-alasan sebagai berikut:
                            <ol class="text-muted" style="text-align: justify;">
                                <li>
                                    <i>Cookies</i> dibutuhkan untuk menjalankan Platform Kami. Ini termasuk, misalnya,
                                    Cookies yang
                                    memungkinkan Anda mengakses Area yang aman di Platform Kami, memakai keranjang belanja,
                                    ataupun menggunakan jasa keuangan eletronik.
                                </li>
                                <li>
                                    <i>Cookies</i> sangat dibutuhkan untuk mengidentifikasi dan menghitung jumlah kunjungan
                                    setiap saat serta memantau bagaimana pengunjung berpindah di lingkungan Platform Kami
                                    pada saat mereka menggunakannya. Ini membantu Kami di dalam memutakhirkan cara kerja
                                    Platform Kami, sebagai contoh, dengan memastikan konsumen menemukan apa yang mereka cari
                                    dengan sangat sederhana.
                                </li>
                                <li>
                                    <i>Cookies</i> Kami butuhkan untuk mengenali Anda ketika kembali ke Platform Kami. Hal
                                    ini memungkinkan Kami melakukan personalisasi terhadap materi Kami untuk Anda, memanggil
                                    Anda dengan nama, serta mencatat preferensi Anda (misalnya, pilihan wilayah atau bahasa
                                    yang Anda gunakan).
                                </li>
                                <li>
                                    <i>Cookies</i> mengingat kunjungan Anda ke Platform Kami, antarmuka yang telah Anda
                                    kunjungi,
                                    serta link yang telah Anda ikuti. Kami akan memanfaatkan informasi ini untuk membangun
                                    Platform Kami serta iklan yang tersambung di dalamnya lebih sesuai minat Anda. Kami juga
                                    dapat berbagi informasi ini kepada pihak ketiga untuk kepentingan tersebut.
                                </li>
                            </ol>
                            </p>

                            <p class="text-muted" style="text-align: justify;">
                                Mohon diperhatikan bahwa pihak ketiga (termasuk, sebagai contohnya, jaringan iklan dan
                                penyedia jasa eksternal seperti jasa analisa lalu lintas situs) juga dapat memakai Cookies,
                                di mana ketentuan yang mungkin berlaku di luar kendali dan tanggung jawab Kami.
                                <i>Cookies</i> ini
                                sangat membantu mempertahankan Platform Kami serta iklan yang ditampilkan di dalamnya agar
                                lebih relevan dan sesuai dengan minat Pengguna, serta meningkatkan kinerja dari Platform
                                Kami.
                                <br>
                                <br>
                                Anda bisa saja menghapus <i>Cookies</i> dengan cara menggunakan fungsi clear data pada
                                aplikasi
                                maupun perambah Anda yang memungkinkan Anda menolak pengaturan sebagian atau seluruh
                                <i>Cookies</i>. Akan tetapi, Anda bisa saja tidak dapat mengakses kembali seluruh atau
                                sebagian
                                Platform Kami.
                            </p>

                            <h3 class="fs-5 card-title">G. Pengakuan dan persetujuan</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Dengan disetujuinya Kebijakan Privasi, Anda menyatakan bahwa Anda sudah membaca dan memahami
                                Kebijakan Privasi ini dan menyetujui seluruh ketentuan yang berlaku di dalamnya. Khususnya,
                                Anda setuju dan memberikan persetujuan Anda kepada kami untuk mengumpulkan, memanfaatkan,
                                membagikan, menyingkapkan, menyimpan, mentransmisikan, atau mengelola Informasi Personal
                                anda sesuai dengan apa yang dijelaskan dalam Kebijakan Privasi ini.
                            </p>
                            <p class="text-muted" style="text-align: justify;">
                                Dalam hal ini Anda menyerahkan Informasi Personal yang terkait dengan individu lain
                                (misalnya Informasi Personal yang terkait dengan pasangan, anggota keluarga, teman, atau
                                pihak lainnya) kepada Kami, maka dengan itu Anda menyatakan serta menjamin bahwa Anda sudah
                                mendapatkan persetujuan dari individu yang bersangkutan dan dengan ini pula menyetujui atas
                                nama individu yang bersangkutan untuk pengumpulan, pemanfaatan, pengungkapan dan
                                pengelolahan Informasi Personal tersebut oleh Kami.
                            </p>

                            <h3 class="fs-5 card-title">H. Konten promosi</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Perubahan apa saja yang Kami terbitkan terhadap Kebijakan Privasi ini di kemudian hari akan
                                diinformasikan melalui halaman ini dan pada saat diperlukan, atau akan diinformasikan kepada
                                Anda ke alamat surat elektronik yang terdaftar. Mohon kunjungi kembali halaman ini dalam
                                jangka beberapa waktu untuk memastikan adanya penyempurnaan atau pemutakhiran dalam
                                Kebijakan Privasi Kami.
                            </p>

                            <h3 class="fs-5 card-title">I. Amandemen kebijakan privasi Kami</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Perubahan apa saja yang Kami terbitkan terhadap Kebijakan Privasi ini di kemudian hari akan
                                diinformasikan melalui halaman ini dan pada saat diperlukan, atau akan diinformasikan kepada
                                Anda ke alamat surat elektronik yang terdaftar. Mohon kunjungi kembali halaman ini dalam
                                jangka beberapa waktu untuk memastikan adanya penyempurnaan atau pemutakhiran dalam
                                Kebijakan Privasi Kami.
                            </p>

                            <h3 class="fs-5 card-title">J. Kontak informasi dan keluhan</h3>
                            <p class="text-muted" style="text-align: justify;">
                                Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau Anda ingin mendapatkan
                                akses dan/atau melakukan koreksi terhadap Informasi Personal Anda, silahkan menghubungi Kami
                                di nomor kontak {{ $web->kontak }}
                            </p>

                            <a href="javascript:window.print()"
                                class="btn btn-pills btn-soft-primary d-print-none">Cetak</a>
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
                'name' => 'Kebijakan Privasi',
                'item' => url()->current(),
            ],
        ];
    @endphp
    {{--  Rich Text BreadcrumbList  --}}
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":<?= json_encode($breadcrumbItemList) ?>}</script>
@endsection
