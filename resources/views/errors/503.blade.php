@extends('errors.layout.index')
@section('title', 'Layanan sedang tidak tersedia')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Layanan sedang tidak tersedia',
        'defaultMessage' =>
            'Layanan ini saat ini sedang tidak tersedia, silahkan coba kenbali beberapa saat lagi. Mohon maaf atas ketidaknyamanannya silahkan kembali halaman utama',
    ])
@endsection
