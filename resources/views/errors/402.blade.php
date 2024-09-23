@extends('errors.layout.index')
@section('title', 'Pembayaran diperlukan')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Oops.. Pembayaran diperlukan !',
        'defaultMessage' => 'Sepertinya Anda belum bisa mengakses halaman ini',
    ])
@endsection
