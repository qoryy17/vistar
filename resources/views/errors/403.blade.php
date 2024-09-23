@extends('errors.layout.index')
@section('title', 'Dilarang')
@section('content')
    @include('errors.layout.content', [
        'title' => 'Oops.. Dilarang !',
        'defaultMessage' => 'Sepertinya Anda tidak memiliki akses ke-halaman ini',
    ])
@endsection
