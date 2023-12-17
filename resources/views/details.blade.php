@php
use App\Models\ProductLike;

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
</head>

<body>

    @include('layouts.navbar')

    <main class="container">
        <div class="mt-4 mb-3">
            <h2>
                {{ $product->title }}
            </h2>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner text-center">
                @foreach($images as $key => $image)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    {!! $image !!}
                </div>
                @endforeach
            </div>
            <button class="carousel-control-prev bg-secondary" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next bg-secondary" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-6">
                <div class="p-4">
                    <h2>Description</h2>
                    <p>{{ $product->content }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4">
                    <h2>Additional info</h2>
                    <p><span class="fw-bold">Category:</span> {{ $product->category }}</p>
                    <p><span class="fw-bold">Available:</span> <span class="text-danger">{{ $product->available }}</span></p>

                    <div class="d-flex flex-row align-items-center mb-1">
                        @if ($product->discount !== NULL && $product->discount != 0)
                        <h4 class="mb-1 me-1">{{ $product->price - (($product->discount * $product->price) / 100) }} $</h4>
                        <span class="text-danger"><s>{{ $product->price }}$</s></span>
                        @else
                        <h4 class="mb-1 me-1">{{ $product->price }}$</h4>
                        @endif
                    </div>

                    <div class="d-flex flex-row align-items-center mb-1">
                        @auth
                        <p class="mr-2 fw-bold" style="margin-right: 10px; margin-top: 5px;">Actions: </p>
                        <div class="mb-2">

                            @php
                            if (Auth::user()) {
                            $like = ProductLike::where([
                            'user_id' => Auth::user()->id,
                            'product_id' => $product->id
                            ])->first();
                            $btn_style = $like ? ($like->is_liked === 1 ? 'btn-primary bg-primary' : 'btn-outline-primary') : 'btn-outline-primary';
                            }
                            @endphp

                            <a href="{{ route('product-like', ['user_id' => Auth::user()->id, 'product_id' => $product->id]) }}" class="btn {{ $btn_style }} btn-sm text-white mb-1">
                                <svg viewBox="0 0 24 24" width="23" height="23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                            </a>

                            <span class="fw-bold text-primary h5 me-3">({{ ProductLike::where(['product_id' => $product->id, 'is_liked' => '1'])->count() }})</span>

                            <button class="btn btn-info btn-sm text-white mb-1" style="margin-right: 5px;">
                                <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" width="24" height="24" xml:space="preserve" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <style type="text/css">
                                            .linesandangles_een {
                                                fill: #000;
                                            }
                                        </style>
                                        <path class="linesandangles_een" d="M25.414,14l-6.707-6.707l-1.414,1.414L22.586,14H9.414l5.293-5.293l-1.414-1.414L6.586,14H4 l2,14h20l2-14H25.414z M24.265,26H7.735L6.306,16h19.388L24.265,26z M11,20H9v-2h2V20z M15,20h-2v-2h2V20z M19,20h-2v-2h2V20z M23,20h-2v-2h2V20z M11,24H9v-2h2V24z M15,24h-2v-2h2V24z M19,24h-2v-2h2V24z M23,24h-2v-2h2V24z"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')

</body>

</html>
