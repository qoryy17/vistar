@extends('errors.layout.index')
@section('title', 'Ada Masalah dengan Server')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Ada Masalah dengan Server',
        'defaultMessage' =>
            'Sepertinya ada kesalahan pada server kami. Mohon maaf atas ketidaknyamanannya silahkan kembali halaman utama',
    ])
@endsection
