@extends('errors.layout.index')
@section('title', 'Halaman tidak ditemukan !')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Oops.. Halaman tidak ditemukan !',
        'defaultMessage' =>
            'Sepertinya halaman yang Anda cari tidak tersedia. Pastikan URL yang Anda masukkan benar atau kembali ke halaman utama',
    ])
@endsection
