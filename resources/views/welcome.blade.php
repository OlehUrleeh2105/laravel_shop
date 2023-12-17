@php
use Illuminate\Support\Facades\Auth;
use App\Models\ProductLike;
use Illuminate\Support\Facades\DB;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    <title>Home page</title>

    <style>
        nav a {
            text-decoration: none;
        }
    </style>

</head>

<body>
    @include("layouts.navbar")

    <main style="margin-top: 40px;">
        <div class="row justify-content-center">
            <div class="list-group list-group-flush d-flex justify-content-center text-center mb-4">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Select Category
                    </button>
                    <div class="dropdown-menu mt-1" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('welcome') }}">All categories</a>

                        @foreach(DB::table('products')->pluck('category')->unique() as $category)
                        <a class="dropdown-item" href="{{ route('welcome', ['category' => $category]) }}">{{ $category }}</a>
                        @endforeach
                    </div>
                </div>
            </div>


            <section>
                <div class="container py-5 bg-secondary rounded-3">
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
                                            @auth
                                            <a href="{{ route('product-like', ['user_id' => Auth::user()->id, 'product_id' => $product->id]) }}" class="btn btn-success btn-sm text-white">
                                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                            @endauth
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

        <div class="d-flex justify-content-center text-decoration-none">
            {{ $products->links() }}
        </div>

        </div>
    </main>

    <div>
        @include("layouts.footer")
    </div>

    <script>
        function rangeMin(value) {
            document.getElementById('rangeMin').innerHTML = value;
        }

        function rangeMax(value) {
            document.getElementById('rangeMax').innerHTML = value;
        }
    </script>

    <script type="text/javascript">
        $('body').on('keyup', '#search-title', function() {
            var searchQuest = $(this).val();

            $.ajax({
                method: 'POST',
                url: '{{ route("search") }}',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    searchQuest: searchQuest,
                },
                success: function(res) {
                    $('#search-products').html('');

                    $.each(res, function(index, product) {
                        var productHtml = '\
                        <div class="col-md-6 col-lg-4 mb-4 mt-2 mb-md-0">\
                            <div class="card h-100">\
                                <div class="d-flex justify-content-between p-3"></div>\
                                \
                                <div class="card-body">\
                                    <img src="' + product.images + '" alt="' + product.category + '" style="max-width: 100%; max-height: 100%;" class="card-img-top img-fluid" />\
                                </div>\
                                \
                                <div class="card-footer p-4 p-0 border-top-0 bg-transparent overflow-auto">\
                                    <div class="d-flex justify-content-between">\
                                        <p class="small"><a href="' + product.category + '" class="text-muted text-decoration-none">' + product.category + '</a></p>\
                                        ' + (product.discount !== null && product.discount != 0.00 ? '<p class="small text-danger"><s>' + product.price + ' $</s></p>' : '') + '\
                                    </div>\
                                    \
                                    <div class="d-flex justify-content-between mb-3">\
                                        <h5 class="mb-0">' + product.title + '</h5>\
                                        <h5 class="text-dark mb-0">' + (product.price - ((product.discount * product.price) / 100)) + ' $</h5>\
                                    </div>\
                                    \
                                    <div class="d-flex justify-content-between mb-2">\
                                        <p class="text-muted mb-0">Available: <span class="fw-bold">' + product.available + '</span></p>\
                                        <div class="ms-auto">\
                                            <a href="/like/' + product.user_id + '/' + product.id + '" class="btn ' + product.liked + ' btn-sm text-white">\
                                                <svg class="text-info" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16">\
                                                    <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/>\
                                                    <path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/>\
                                                </svg>\
                                            </a>\
                                            <button class="btn btn-info btn-sm text-white">\
                                                <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">\
                                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>\
                                                </svg>\
                                            </button>\
                                            <a href="' + '/details/' + product.id + '" class="btn btn-secondary btn-sm">\
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">\
                                                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>\
                                                </svg>\
                                            </a>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>';


                        $('#search-products').append(productHtml);
                    })
                }
            })
        });
    </script>

</body>

</html>
