<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        use App\Models\PengaturanWeb;
        $web = PengaturanWeb::all()->first();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author }}">
    <meta name="keywords" content="{{ $web->meta_keyword }}">
    <meta name="description" content="{{ $web->meta_description }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/' . $web->logo) }}" type="image/png" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
</head>

<body style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400;">


    <!-- Hero Start -->
    <div style="margin-top: 50px;">
        <table cellpadding="0" cellspacing="0"
            style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
            <thead>
                <tr
                    style="background-color: #2f55d4; padding: 3px 0; text-align: center; color: #fff; font-size: 24px; letter-spacing: 1px;">
                    <th scope="col">
                        <h5>{{ $web->nama_bisnis }} <br><small style="font-size: 8px;">{{ $web->tagline }}</small></h5>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="padding: 24px 24px;">
                        <div
                            style="padding: 8px; color: #0075B8;text-align: center; font-size: 16px; font-weight: 600;">
                            Maaf, Pengajuan Tryout Gratis Anda Ditolak ! <br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 24px 15px; color: #8492a6;">
                        <div>
                            Tidak memenuhi persyaratan dalam pengajuan tryout gratis. Silahkan mengajukan ulang tryout
                            gratis !
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 24px 15px; color: #8492a6;">
                        Jika ingin menikmati fitur lengkap tryout yang tersedia <br> silahkan beli paket tryout berbayar
                        pada {{ $web->nama_bisnis }}
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 24px 15px; color: #8492a6;">
                        <small>Silahkan login dan pilih menu <strong>Akun</strong> kemudian Tryout Gratis</small>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 15px 24px 15px; color: #8492a6;">
                        {{ $web->nama_bisnis }} <br> Support Team
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px 24px 15px; color: #8492a6; text-align: center;">
                        <small>Mohon untuk tidak membalas pesan otomatis ini !</small>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ $web->nama_bisnis }}.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Hero End -->
</body>

</html>
