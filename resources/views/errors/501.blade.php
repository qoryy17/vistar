@extends('errors.layout.index')
@section('title', 'Permintaan tidak tersedia')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Permintaan tidak tersedia',
        'defaultMessage' =>
            'Sepertinya ada permintan anda belum tersedia. Mohon maaf atas ketidaknyamanannya silahkan kembali halaman utama',
    ])
@endsection
