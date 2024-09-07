@php
    $web = \App\Helpers\BerandaUI::web();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="{{ $web->meta_author }}">
    <meta name="keywords" content="{{ $web->meta_keyword }}">
    <meta name="description" content="{{ $web->meta_description }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/' . $web->logo) }}" type="image/png" />

    <!-- Title -->
    <title>{{ $title }}</title>

    <!-- Css -->
    <!-- Bootstrap Css -->
    <link href="{{ url('resources/web/dist/assets/css/bootstrap.min.css') }}" id="bootstrap-style" class="theme-opt"
        rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ url('resources/web/dist/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ url('resources/web/dist/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css"
        rel="stylesheet">
    <!-- Internal Sweet-Alert css-->
    <link href="{{ url('resources/spruha/assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet">

    <!-- Style Css-->
    <link href="{{ url('resources/web/dist/assets/css/style.min.css') }}" id="color-opt" class="theme-opt"
        rel="stylesheet" type="text/css">

    <style type="text/css">
        @media only screen and (max-width: 600px) {
            #bg-paralax {
                display: none;
            }
        }

        #img-logo {
            max-width: 10rem;
        }
    </style>
</head>
