@php

    use App\Models\ProductOrder;
    use Illuminate\Support\Facades\Auth;

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body>
    
    @auth
        @if(Auth::user()->is_admin === 1) 
            @include('admin.layouts.navbar')
        @else
            @include('layouts.navbar')
        @endif
    @endauth

    <section class="h-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-10 col-xl-8">
                    <div class="card" style="border-radius: 10px;">
                        <div class="card-header px-4 py-5">
                            <h5 class="text-muted mb-0">Order: <span style="color: #102e2e;">{{ $order_id }}</span></h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <p class="lead fw-normal mb-0" style="color: #102e2e;">Receipt</p>
                                <p class="small text-muted mb-0">Receipt Voucher : 1KAU9-84UIL</p>
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
                                                $order_info = ProductOrder::where(['order_id' => $order_id, 'product_id' => $product->id])->first();
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
                                                $totalDiscount += $order_info->total_discount;
                                                $totalPrice += $order_info->total_price;
                                            @endphp

                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">Discount: ${{ $order_info->total_discount }}</p>
                                            </div>
                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">${{ $order_info->total_price }}</p>
                                            </div>
                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small me-5">Qty: {{ $order_info->total_quantity }}</p>
                                            </div>
                                        </div>
                                        <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-md-2 text-center mb-4">
                                                <p class="text-muted mb-0 small">Track Order</p>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="progress" style="height: 6px; border-radius: 16px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ ($order_info->status === 'in_progress' ? rand(0, 35) : ($order_info->status === 'half_way' ? rand(36, 70) : rand(71, 100))) }}%; border-radius: 16px; background-color: #102e2e;"
                                                    aria-valuenow="{{ ($order_info->status === 'in_progress' ? rand(0, 35) : ($order_info->status === 'half_way' ? rand(36, 70) : rand(71, 100))) }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="d-flex justify-content-around mb-1">
                                                    <p class="text-muted mt-1 mb-0 small">In progress</p>
                                                    <p class="text-muted mt-1 mb-0 small ms-xl-2">On a half way</p>
                                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Delivered</p>
                                                </div>
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
                                <p class="text-muted mb-0">Invoice Number : 788152</p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Discount</span> ${{ $totalDiscount }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="text-muted mb-0">Invoice Date : {{ date('l jS \of F Y '); }}</p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">GST 20%</span> ${{ $totalTax = round(($totalPrice - $totalDiscount) * 0.20) }}</p>
                            </div>

                            <div class="d-flex justify-content-between mb-5">
                                <p class="text-muted mb-0">Recepits Voucher : 18KU-62IIK</p>
                                <p class="text-muted mb-0"><span class="fw-bold me-4">Delivery Charges</span> Free</p>
                            </div>
                        </div>
                        <div class="card-footer border-0 px-4 py-5"
                            style="background-color: #102e2e; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                            <div></div>
                            <h5 class="text-white text-uppercase mb-0">Total paid: <span class="h2 mb-0 ms-2">${{ $totalPrice - $totalDiscount + $totalTax }}</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>