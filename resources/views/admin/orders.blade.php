@php

    use App\Models\Product;

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
</head>
<body>

    @include('admin.layouts.navbar')



    <section>
        <div class="container py-5">
            <div class="d-flex mb-3 justify-content-center">
                <span class="me-2">Sort By:</span>
                <div class="dropdown">
                    <button type="submit" class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Order Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="{{ route('admin.orders', ['status' => 'in_progress']) }}">In progress</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.orders', ['status' => 'half_way']) }}">On a half way</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.orders', ['status' => 'delivered']) }}">Delivered</a></li>
                    </ul>
                </div>
            </div>

            <div class="row justify-content-center bg-secondary rounded-3 p-5" id="order-search">
                @foreach(array_keys($products) as $order_id)
                    <div class="col-md-8 col-lg-6 col-xl-4 mb-2" id="{{ $order_id }}">
                        <div class="card text-black">
                            <div class="card-body">
                                <div class="text-center">
                                    <p class="text-muted mb-4">Order Number: <a href="{{ route('orders.detail', ['id' => $order_id]) }}" title="Go to check details of {{ $order_id }}" class="fw-bold text-dark">{{ $order_id }}</a></p>
                                </div>
                                @php
                                    $total = 0;
                                    $totalDiscount = 0;
                                @endphp
                                @foreach($products[$order_id] as $order)
                                    @php
                                        $product = Product::find($order->product_id);
                                        $totalDiscount += $order->total_discount;
                                        $total += $order->total_price;
                                    @endphp
                                    <div class="d-flex justify-content-between">
                                        <span>{{ $product->title }} <span class="fw-bold">(x{{ $order->total_quantity }})</span></span><span>${{ $order->total_price }}</span>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between total font-weight-bold mt-4">
                                    <div class="dropdown">
                                        <button type="submit" class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Order Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="{{ route('admin.update.status', ['id' => $order_id, 'status' => 'in progress']) }}">In progress</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.update.status', ['id' => $order_id, 'status' => 'half_way']) }}">On a half way</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.update.status', ['id' => $order_id, 'status' => 'delivered']) }}">Delivered</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between total font-weight-bold mt-4">
                                    <span class="fw-bold text-dark">Total Discount</span><span>${{ $totalDiscount }}</span>
                                </div>
                                <div class="d-flex justify-content-between total font-weight-bold mt-1">
                                    <span class="fw-bold text-dark">GST 20%</span><span>${{ round(($total - $totalDiscount) * 0.20)  }}</span>
                                </div>
                                <div class="d-flex justify-content-between total font-weight-bold mt-1">
                                    <span class="fw-bold text-dark">Total</span><span>${{ $total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</body>
</html>
