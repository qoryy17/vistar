@extends('errors.layout.index')
@section('title', 'Kredensial autentikasi anda tidak valid')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Oops.. Kredensial autentikasi anda tidak valid !',
        'defaultMessage' => 'Sepertinya Anda tidak memiliki akses ke-halaman ini',
    ])
@endsection
