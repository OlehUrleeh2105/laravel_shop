<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    @include('admin.layouts.navbar')

    <div class="container mt-4 bg-secondary rounded-3 p-3">
        <h3 class="text-center">Add a New Product</h3>
        <form class="mt-2" method="POST" action="{{ route('admin.add_product') }}" enctype="multipart/form-data">
            @csrf

            <div class="row mb-4">
                <div class="col">
                    <div class="form-outline">
                        <input type="text" class="form-control" id="title" placeholder="Title" name="title"
                            required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="text" class="form-control" id="category" placeholder="Category" name="category"
                            required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <textarea class="form-control" id="content" name="content" placeholder="Content" required></textarea>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-outline">
                        <input type="number" class="form-control" id="available" placeholder="In stock"
                            name="available" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="number" class="form-control" id="discount" placeholder="Discount (%) (optional)"
                            name="discount" step="0.1">
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <input type="number" class="form-control" id="price" placeholder="Price" name="price"
                            step="20" required>
                    </div>
                </div>
            </div>

            <div class="mt-2 mb-3">
                <label for="images" class="form-label">Product Images</label>
                <input type="file" class="form-control form-control-sm" name="images[]" multiple required>
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</body>

</html>
