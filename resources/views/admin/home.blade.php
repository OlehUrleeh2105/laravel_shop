<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>

    <style>
        @media (max-width: 767.98px) { .border-sm-start-none { border-left: none !important; } }

        nav a {
            text-decoration: none;
        }
    </style>

</head>
<body>
    @include('admin.layouts.navbar')

    <section>
        <div class="container py-5 bg-secondary mt-5 rounded-3">

            @foreach($products as $product)
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

            <div class="row justify-content-center mb-3">
                <div class="col-md-12 col-xl-10">
                    <div class="card shadow-0 border rounded-3">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                    @if ($imagePath)
                                        <img class="card-img-top" src="{{ $imagePath }}" alt="{{ $product->title }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <h5>{{ $product->title }}</h5>
                                <div class="d-flex flex-row">
                                    <span class="me-2">In stocks: </span>
                                    <div class="text-danger mb-1">
                                        {{ $product->available }}
                                    </div>
                                </div>
                                <div class="mt-1 mb-0 text-muted small">
                                    <span>Category: {{ $product->category }}</span>
                                </div>
                                <p class="text-truncate mb-4 mb-md-0">
                                    Description: {{ $product->content }}
                                </p>
                            </div>
                                <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                    <div class="d-flex flex-row align-items-center mb-1">
                                        @if ($product->discount !== NULL && $product->discount != 0)
                                            <h4 class="mb-1 me-1">{{ $product->price - (($product->discount * $product->price) / 100) }} $</h4>
                                            <span class="text-danger"><s>{{ $product->price }}$</s></span>
                                        @else
                                            <h4 class="mb-1 me-1">{{ $product->price }}$</h4>
                                        @endif
                                    </div>

                                    <h6 class="text-success">Free shipping</h6>
                                    <div class="d-flex flex-column mt-4">
                                        <a href="{{ url('/admin/edit/' . $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ url('/admin/delete/' . $product->id) }}" class="btn btn-outline-danger btn-block mt-2">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="d-flex justify-content-center mt-2 text-decoration-none">
                {{ $products->links() }}
            </div>

        </div>
    </section>
</body>
</html>
