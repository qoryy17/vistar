@extends('errors.layout.index')
@section('title', 'Status Server tidak mendukung')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Status Server tidak mendukung',
        'defaultMessage' =>
            'Sepertinya ada kesalahan pada server kami. Mohon maaf atas ketidaknyamanannya silahkan kembali halaman utama',
    ])
@endsection
