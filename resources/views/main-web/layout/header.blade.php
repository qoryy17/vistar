<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        use App\Helpers\BerandaUI;
        $web = BerandaUI::web();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author ? $web->meta_author : '' }}">
    <meta name="keywords" content="{{ $web->meta_keyword ? $web->meta_keyword : '' }}">
    <meta name="description" content="{{ $web->meta_description ? $web->meta_description : '' }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ $web->logo ? asset('storage/' . $web->logo) : '' }}" type="image/png" />

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



</head>

<body>
