@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();

    $prefersColorScheme = 'light';
    $themeColor = '#ffffff';
    if ($prefersColorScheme == 'dark') {
        $themeColor = '#000000';
    }

    $logo = asset('resources/images/logo.png');
    $metaImage = asset('resources/images/logo.png');
    $uploadedLogo = 'storage/' . $web->logo;
    if (is_file($uploadedLogo)) {
        $metaImage = asset($uploadedLogo);
        $logo = asset($uploadedLogo);
    }
    if (View::hasSection('image')) {
        $metaImage = View::getSection('image');
    }

    $title = View::getSection('title');

    $keywords = implode(
        ', ',
        array_filter(
            array_unique(
                array_merge(
                    explode(', ', strtolower($web->meta_keyword ? $web->meta_keyword : '')),
                    explode(', ', strtolower(View::getSection('keywords'))),
                ),
                SORT_REGULAR,
            ),
            'strlen',
        ),
    );

    $metaDescription = $web->meta_description ? $web->meta_description : '';
    if (View::hasSection('description')) {
        $metaDescriptionSection = View::getSection('description');
        if (strlen($metaDescriptionSection) < 170) {
            $metaDescription = $metaDescriptionSection . ' :. ' . $metaDescription;
        }
    } else {
        $metaDescription = $title . ' :. ' . $metaDescription;
    }
    if (strlen($metaDescription) > 170) {
        $metaDescription = substr($metaDescription, 0, 168) . '..';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="author" content="{{ $web->meta_author ? $web->meta_author : '' }}">
    <meta name="keywords" content="{{ $keywords }}">

    <meta name="description" content="{{ $metaDescription }}">

    <link rel="canonical" href="{{ url()->current() }}" />

    <meta name="apple-mobile-web-app-title" content="Vistar">
    <meta name="application-name" content="Vistar">

    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="msapplication-TileImage" content="{{ asset('resources/images/icons/ms-icon-144x144.png') }}" />

    <meta name="theme-color" content="{{ $themeColor }}" />

    <link rel="icon" type="image shortcut" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png shortcut" sizes="16x16"
        href="{{ asset('resources/images/icons/favicon-16x16.png') }}" />
    <link rel="icon" type="image/png shortcut" sizes="32x32"
        href="{{ asset('resources/images/icons/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png shortcut" sizes="96x96"
        href="{{ asset('resources/images/icons/favicon-96x96.png') }}" />
    <link rel="icon" type="image/png shortcut" sizes="192x192"
        href="{{ asset('resources/images/icons/icon-192x192.png') }}" />

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('resources/images/icons/apple-icon-57x57.png') }}" />
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('resources/images/icons/apple-icon-60x60.png') }}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('resources/images/icons/apple-icon-72x72.png') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('resources/images/icons/apple-icon-76x76.png') }}" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('resources/images/icons/apple-icon-114x114.png') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('resources/images/icons/apple-icon-120x120.png') }}" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('resources/images/icons/apple-icon-144x144.png') }}" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('resources/images/icons/apple-icon-152x152.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('resources/images/icons/apple-icon-180x180.png') }}" />

    <link rel="mask-icon" href="{{ asset('resources/images/icons/safari-pinned-tab.svg') }}" color="#5bbad5">

    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:image" content="{{ $metaImage }}" />
    <meta property="og:description" content="{{ $metaDescription }}" />

    {{--  Twitter Meta Tags  --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="{{ request()->getHost() }}">
    <meta property="twitter:url" content="{{ url()->full() }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    {{--  Note: Create App Config APP ID after vistar using social account login with facebook --}}
    <meta property="fb:app_id" content="1235512704325801" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Bootstrap Css -->
    <link href="{{ asset('resources/web/dist/assets/css/bootstrap.min.css') }}" id="bootstrap-style"
        class="theme-opt" rel="stylesheet" type="text/css">

    @yield('styles-top')

    <!-- Css -->
    <link href="{{ asset('resources/web/dist/assets/libs/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('resources/web/dist/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('resources/web/dist/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Internal Sweet-Alert css-->
    <link href="{{ url('resources/spruha/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">
    <!-- Style Css-->
    <link href="{{ asset('resources/web/dist/assets/css/style.min.css') }}" rel="stylesheet" type="text/css">

    @yield('styles')

    {{--  Rich Text Organization  --}}
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "image": "{{ $logo }}",
          "url": "{{ url()->route('mainweb.index') }}",
          "sameAs": ["{{ $web->facebook }}", "{{ $web->instagram }}"],
          "logo": "{{ $logo }}",
          "name": "{{ $web->nama_bisnis }}",
          "legalName": "{{ $web->perusahaan }}",
          "description": "{{ $web->meta_description }}",
          "email": "{{ $web->email }}",
          "telephone": "{{ $web->kontak }}",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $web->alamat }}",
            "addressRegion": "Sumatera Utara",
            "addressLocality": "Kota Medan",
            "postalCode": "20219",
            "addressCountry": "ID"
          },
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "{{ $web->kontak }}",
            "email": "{{ $web->email }}"
          }
        }
    </script>
</head>

<body>
