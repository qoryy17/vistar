@php
    use App\Helpers\BerandaUI;
    $web = BerandaUI::web();

    $prefersColorScheme = 'light';
    $themeColor = '#ffffff';
    if ($prefersColorScheme == 'dark') {
        $themeColor = '#000000';
    }

    $logo = 'resources/images/vistar-indonesia.png';
    $uploadedLogo = 'storage/' . $web->logo;
    if (is_file($uploadedLogo)) {
        $logo = $uploadedLogo;
    }

    $keywords = $web->meta_keyword ? $web->meta_keyword : '';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author ? $web->meta_author : '' }}">
    <meta name="keywords"
        content="@hasSection('keywords')
@yield('keywords'), {{ $keywords }}
@else
{{ $keywords }}
@endif">
    <meta name="description" content="@yield('description', $web->meta_description ? $web->meta_description : '')">

    <link rel="canonical" href="{{ url()->current() }}" />

    <meta name="msapplication-TileColor" content="{{ $themeColor }}" />
    <meta name="msapplication-TileImage" content="{{ asset('resources/images/icons/ms-icon-144x144.png') }}" />

    <meta name="theme-color" media="{{ 'prefers-color-scheme: ' . $prefersColorScheme }}"
        content="{{ $themeColor }}" />

    <link rel="icon" type="image shortcut" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('resources/images/icons/favicon-16x16.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('resources/images/icons/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ asset('resources/images/icons/favicon-96x96.png') }}" />
    <link rel="icon" type="image/png" sizes="192x192"
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

    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:image" content="@yield('image', asset($logo))" />
    <meta property="og:description" content="@yield('description', $web->meta_description ? $web->meta_description : '')" />

    {{--  Note: Create App Config APP ID after vistar using social account login with facebook --}}
    <meta property="fb:app_id" content="1235512704325801" />

    <link rel="manifest" href="{{ asset('manifest.json') }}" />

    <!-- Title -->
    <title>@yield('title')</title>

    <!-- Css -->
    <link href="{{ asset('resources/web/dist/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/web/dist/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/web/dist/assets/libs/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/web/dist/assets/libs/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('resources/web/dist/assets/css/bootstrap.min.css') }}" id="bootstrap-style" class="theme-opt"
        rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('resources/web/dist/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('resources/web/dist/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Style Css-->
    <link href="{{ asset('resources/web/dist/assets/css/select2.min.css') }}" id="color-opt" class="theme-opt"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('resources/web/dist/assets/css/style.min.css') }}" id="color-opt" class="theme-opt"
        rel="stylesheet" type="text/css">

    @yield('styles')
</head>

<body>
