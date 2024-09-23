@extends('errors.layout.index')
@section('title', 'Waktu Permintaan Habis')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Waktu Permintaan Habis',
        'defaultMessage' =>
            'Sepertinya ada server kami kehabisan waktu untuk memproses permintaan anda, silahkan coba kembali atau kembali halaman utama',
    ])
@endsection
