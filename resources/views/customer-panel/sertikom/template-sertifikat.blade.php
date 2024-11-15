<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title . ' ' . $participant->nama }}</title>
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com"> --}}
    {{-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        /* Ukuran halaman A4 landscape */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap');

        /* Reset margin body dan atur background */
        body {
            color: #243480;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-image: url("{{ public_path('resources/images/Blank-Sertifikat.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .container {
            margin: 10px;
        }

        .header-certificate {
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .logo-certificate {
            max-width: 200px;
        }

        .number-certificate {
            margin: 0;
            padding: 0;
            color: #243480;
        }

        .body-certificate h1 {
            margin: 0;
            padding: 0;
            font-size: 35px;
            color: #FD9002;
        }

        .number-certificate h4 {
            margin: 0 0 20px 0;
        }

        .inside-certificate h4 {
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .inside-certificate h2 {
            /* font-family: "Great Vibes", cursive; */
            /* font-family: 'Great Vibes';*/

            font-family: 'Great Vibes', cursive;
            font-size: 35px;
            margin: 10px 0 10px 0;
        }

        .inside-certificate hr {
            width: 55%;
            margin: 0 auto 0 auto;
        }

        .note-certificate {
            font-weight: normal;
        }

        .author-certificate {
            position: fixed;
            top: 75%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .author-ttd {
            max-width: 450px;
            position: fixed;
            top: 86%;
            left: 70%;
            transform: translate(-50%, -50%);
        }

        .author-name {
            margin: 80px 0px 0px;
            padding: 0;
        }

        .author-position {
            margin-top: 5px;
            font-weight: normal;
        }

        .footer-certificate {
            text-align: left;
            position: fixed;
            left: 10%;
            top: 86%;
        }

        .footer-certificate h5 {
            margin: 0;
        }

        .footer-certificate p {
            font-size: 11px;
            margin: 0;
        }

        .qrcode-certificate {
            max-width: 100px;
            align-items: left;
        }
    </style>
</head>

<body>
    @php
        $web = App\Helpers\BerandaUI::web();
    @endphp
    <div class="container">
        <div class="header-certificate">
            <img class="logo-certificate" src="{{ public_path('resources/images/logo.png') }}" alt="">
        </div>
        <div class="body-certificate">
            <h1>{{ strtoupper($title) }}</h1>
            <div class="number-certificate">
                <h4>No. {{ $certificate }}</h4>
            </div>
            <div class="inside-certificate">
                <h4>Diberikan kepada</h4>
                <h2>{{ $participant->nama }}</h2>
                <hr>
            </div>
            <h4 class="note-certificate">{{ $note }}</h4>
            <h2 style="margin: 0 50px 10px 100px;">
                {{ strtoupper($product->produk) }}
            </h2>
            <p>
                Yang di selenggarakan pada tanggal :
                @php
                    $tanggal_mulai = $product->tanggal_mulai;
                    $tanggal_selesai = $product->tanggal_selesai;
                @endphp
                @if ($tanggal_mulai != $tanggal_selesai)
                    {{ Carbon\Carbon::parse($product->tanggal_mulai)->format('d F Y') }} sampai
                    {{ Carbon\Carbon::parse($product->tanggal_selesai)->format('d F Y') }}
                @else
                    {{ Carbon\Carbon::parse($product->tanggal_mulai)->format('d F Y') }}
                @endif
                oleh {{ $web->nama_bisnis }}
                yang merupakan <br> Platform Edukasi Bidang IT
                Terbaik #1 di Indonesia di bawah legalitas {{ $web->perusahaan }} <br>
                Nomor AHU : <strong>0029699.AH.01.01 Tahun 2020</strong>
            </p>

            <div class="author-certificate">
                {{-- {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate(Request::url()) !!} --}}
                <h4>Medan,
                    {{ Carbon\Carbon::parse($product->tanggal_selesai)->format('d F Y') }}</h4>
                <img class="author-ttd" src="{{ public_path('resources/images/ttd.png') }}" alt="">
                <h4 class="author-name">Dr. Dicky Nofriansyah, S.Kom.,M.Kom</h4>
                <hr style="margin: 2px auto 0 auto;" width="350px" align="center">
                <h5 class="author-position">Direktur Utama PT. Bungkus Teknologi Indonesia</h5>
            </div>
        </div>
        <div class="footer-certificate">
            <table>
                <tr>
                    <td> <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code {{ $qrCode }}"></td>
                    <td>
                        <h5>Verifikasi Sertikat</h5>
                        <p>{{ $urlVerification }}
                        </p>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
