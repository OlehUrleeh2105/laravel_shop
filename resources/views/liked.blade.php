@php

use App\Models\ProductLike;

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked Products</title>
</head>

<body>

    @include('layouts.navbar')

    <div class="row justify-content-center">
        <section>
            <div class="container mt-4 p-4 bg-secondary rounded-3">
                <div class="row" id="search-products">
                    @foreach($products as $product)
                    <div class='product-card col-md-6 col-lg-4 mb-4 mt-2 mb-md-0'>
                        <div class="card h-100">
                            <div class="d-flex justify-content-between p-3"></div>
                            @php
                            $postImageFolder = public_path('assets/' . $product->id);
                            $imagePath = null;
                            if (is_dir($postImageFolder)) {
                            $images = scandir($postImageFolder);
                            foreach ($images as $image) {
                            if (!in_array($image, ['.', '..'])) {
                            $imagePath = asset('assets/' . $product->id . '/' . $image);
                            break;
                            }
                            }
                            }
                            @endphp
                            <div class="card-body">
                                <img src="{{ $imagePath }}" alt="{{ $product->category }}" style="max-width: 100%; max-height: 100%; " class="card-img-top img-fluid" />
                            </div>
                            <div class="card-footer p-4 p-0 border-top-0 bg-transparent overflow-auto">
                                <div class="d-flex justify-content-between">
                                    <p class="small"><a href="{{ route('welcome', ['category' => $product->category]) }}" class="text-muted text-decoration-none">{{ $product->category }}</a></p>
                                    @if ($product->discount !== null && $product->discount != 0.00)
                                    <p class="small text-danger"><s>{{ $product->price }} $</s></p>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="mb-0">{{ $product->title }}</h5>
                                    <h5 class="text-dark mb-0">{{ $product->price - (($product->discount * $product->price) / 100) }} $</h5>
                                </div>
                                @php
                                if (Auth::user()) {
                                $like = ProductLike::where([
                                'user_id' => Auth::user()->id,
                                'product_id' => $product->id
                                ])->first();
                                $btn_style = $like ? ($like->is_liked === 1 ? 'btn-primary bg-primary' : 'btn-outline-primary') : 'btn-outline-primary';
                                }
                                @endphp

                                <div class="d-flex justify-content-between mb-2">
                                    <p class="text-muted mb-0">Available: <span class="fw-bold">{{ $product->available }}</span></p>
                                    <div class="ms-auto">
                                        <a href="{{ route('product-like', ['user_id' => Auth::user()->id, 'product_id' => $product->id]) }}" class="btn btn-success btn-sm text-white">
                                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </g>
                                            </svg>
                                        </a>
                                        <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="btn btn-warning btn-sm">
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
                                        </a>
                                        <a href="{{ route('details', [ 'id' => $product->id ]) }}" class="btn btn-secondary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    @include('layouts.footer')

</body>

</html>
