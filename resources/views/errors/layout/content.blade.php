<div class="container">
    <div class="construction1 text-center details text-white">
        <div class="">
            <div class="col-lg-12">
                <h1 class="tx-140 mb-0">{{ $exception->getStatusCode() }}</h1>
            </div>
            <div class="col-lg-12 ">
                <h1>{{ $title }}</h1>
                <h2 class="tx-15 mt-3 mb-4">
                    {{ $exception->getMessage() ? $exception->getMessage() : $defaultMessage }}
                </h2>
                @php
                    $buttonUrl = route('mainweb.index');
                    $buttonText = 'Halaman Depan';
                    if (url()->previous() !== url()->previous()) {
                        $buttonUrl = url()->previous();
                        $buttonText = $buttonText;
                    }
                @endphp
                @if (url()->current() !== $buttonUrl)
                    <a class="btn btn-warning text-center mb-2" href="{{ $buttonUrl }}">
                        <i class="fa fa-reply"></i> {{ $buttonText }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
