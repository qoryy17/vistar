@extends('errors.layout.index')
@section('title', 'Permintaan tidak dapat diproses')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Permintaan tidak dapat diproses',
        'defaultMessage' =>
            'Sepertinya ada kesalahan pada server kami. Mohon maaf atas ketidaknyamanannya silahkan kembali halaman utama',
    ])
@endsection
