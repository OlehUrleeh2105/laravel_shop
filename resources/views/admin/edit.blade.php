<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>

<body>

    @include('admin.layouts.navbar')

    <div class="container mt-4 bg-secondary rounded-3 p-3">
        <h3 class="text-center">Add a New Product</h3>
        <form class="mt-2" method="POST" action="{{ route('admin.edit_product', ['id' => $product->id]) }}"
            enctype="multipart/form-data">
            @csrf

            <div class="row mb-4">
                <div class="col">
                    <div class="form-outline">
                        <input type="text" value="{{ $product->title }}" class="form-control" id="title"
                            placeholder="Title" name="title" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="text" value="{{ $product->category }}" class="form-control" id="category"
                            placeholder="Category" name="category" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <textarea class="form-control" id="content" name="content" placeholder="Content" required>{{ $product->content }}</textarea>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-outline">
                        <input type="number" value="{{ $product->available }}" class="form-control" id="available"
                            placeholder="In stock" name="available" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="number"
                            value="{{ $product->discount !== null && $product->discount !== 0 ? $product->discount : 0, 0 }}"
                            class="form-control" id="discount" placeholder="Discount (%)" name="discount"
                            step="0.1">
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="number" value="{{ $product->price }}" class="form-control" id="price"
                            placeholder="Price" name="price" step="20" required>
                    </div>
                </div>
            </div>

            <div id="carouselExampleIndicators" class="carousel slide mt-4" data-bs-ride="carousel">
                <div class="carousel-inner text-center">
                    @php
                        $productFolder = public_path('assets/' . $product->id);

                        if (is_dir($productFolder)) {
                            $images = scandir($productFolder);
                            $imageTags = [];

                            foreach ($images as $image) {
                                if (!in_array($image, ['.', '..']) && is_file($productFolder . '/' . $image)) {
                                    $imageTags[] = $image;
                                }
                            }
                        }
                    @endphp

                    @foreach ($imageTags as $key => $image)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('assets/' . $product->id . '/' . $image) }}" style="height: 400px;">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>{{ $image }}</h5>
                                <a href="{{ route('admin.deleteImage', ['id' => $product->id, 'image' => $image]) }}"
                                    class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev bg-secondary" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next bg-secondary" type="button"
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="mt-2 mb-3">
                <label for="images" class="form-label">Add product images</label>
                <input type="file" class="form-control form-control-sm" name="images[]" multiple>
            </div>

            <button type="submit" class="btn btn-primary mb-5">Edit Product</button>
        </form>
    </div>

</body>

</html>
