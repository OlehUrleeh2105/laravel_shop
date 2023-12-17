@php
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>

<body>
    @include('layouts.navbar')

    <section class="h-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <div class="card" style="border-radius: 10px 10px 0 0;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <p class="lead fw-normal mb-0" style="color: #102e2e;">Receipt</p>
                            </div>
                            @php
                            $totalDiscount = 0;
                            $totalPrice = 0;
                            $totalTax = 0;
                            @endphp
                            @foreach($products as $product)
                            <div class="card shadow-0 border mb-4">
                                <div class="card-body">
                                    <div class="row">
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
                                        <div class="col-md-2">
                                            <img src="{{ $imagePath }}" class="img-fluid" alt="{{ $product->title }}">
                                        </div>
                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0">{{ $product->title }}</p>
                                        </div>
                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0 small">{{ $product->category }}</p>
                                        </div>

                                        @php
                                        $discount = round(($product->price * $product->discount) / 100) * $product->quantity;
                                        $total = round($product->price) * $product->quantity;

                                        $totalDiscount += $discount;
                                        $totalPrice += $total;
                                        @endphp

                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0 small">Discount: ${{ $discount }}</p>
                                        </div>
                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0 small">${{ $total }}</p>
                                        </div>
                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0 small me-5">Qty: {{ $product->quantity }}</p>
                                            <a href="{{ route('cart.delete', ['id' => $product->id]) }}" class="mb-0 small text-decoration-none text-danger">X</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div class="d-flex justify-content-between pt-2">
                                <p class="fw-bold mb-0">Order Details</p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Total</span> ${{ $totalPrice }}</p>
                            </div>

                            <div class="d-flex justify-content-between pt-2">
                                <p class="text-muted mb-0"></p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Discount</span> ${{ $totalDiscount }}</p>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer border-0 px-4 py-5 bg-primary" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <h5 class="text-white text-uppercase mb-0">Total paid: <span class="h2 mb-0 ms-2">${{ $totalPrice - $totalDiscount + $totalTax }}</span></h5>
                        <a href="{{ route('cart.order') }}" class="btn btn-outline-light">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <div class="container">

        <div class="row justify-content-center">
            <section>
                <div class="container py-5 rounded-3 bg-secondary">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>



    @include('layouts.footer')



</body>

</html>
