@php
    use App\Models\Product;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>Orders</title>
</head>
<body>

    @include('layouts.navbar')

    <section>
        <div class="container py-5">
            <div class="row justify-content-center bg-secondary p-5 rounded-3" id="order-search">
                @foreach(array_keys($ordersMap) as $order_id)
                    <div class="col-md-8 col-lg-6 col-xl-4 mb-2" id="{{ $order_id }}">
                        <div class="card text-black">
                            <div class="card-body">
                                @php
                                    $total = 0;
                                    $totalDiscount = 0;
                                @endphp
                                @foreach($ordersMap[$order_id] as $order)
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
                                    <span class="fw-bold text-dark">Total Discount</span><span>${{ $totalDiscount }}</span>
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


<script type="text/javascript">
    $('body').on('keyup', '#search-title', function() {
        var searchQuest = $(this).val();

        $.ajax({
            method: 'POST',
            url: '{{ route("orders.search") }}',
            dataType: 'json',
            data: {
                '_token':  '{{ csrf_token() }}',
                searchQuest: searchQuest,
            },
            success: function(res) {
                $('#order-search').html('');

                $.each(res.orders, function(idx, order) {
                    var orderHtml = '\
                        <div class="col-md-8 col-lg-6 col-xl-4 mb-2">\
                            <div class="card text-black">\
                                <div class="card-body">\
                                    <div class="text-center">\
                                        <p class="text-muted mb-4">Order Number: <a href="/orders/detail/' + order.order_id + '" title="Go to check details of ' + order.order_id + '" class="fw-bold text-dark">' + order.order_id + '</a></p>\
                                    </div>';

                    var total = 0;
                    var totalDiscount = 0;

                    $.each(res.products, function(idx, product) {
                        if (product.order_id === order.order_id) {
                            total += parseInt(product.total_price);
                            totalDiscount += parseInt(product.total_discount);

                            console.log(totalDiscount)

                            orderHtml += '\
                                <div class="d-flex justify-content-between">\
                                    <span>' + product.title + '<span class="fw-bold">(x' + product.quantity + ')</span></span><span>$' + product.total_price + '</span>\
                                </div>';
                        }
                    });

                    var totalTax = parseInt((total - totalDiscount) * 0.2);

                    orderHtml += '\
                                <div class="d-flex justify-content-between total font-weight-bold mt-4">\
                                    <span class="fw-bold text-dark">Total Discount</span><span>$' + totalDiscount + '</span>\
                                </div>\
                                <div class="d-flex justify-content-between total font-weight-bold mt-1">\
                                    <span class="fw-bold text-dark">GST 20%</span><span>$' + totalTax + '</span>\
                                </div>\
                                <div class="d-flex justify-content-between total font-weight-bold mt-1">\
                                    <span class="fw-bold text-dark">Total</span><span>$' + total + '</span>\
                                </div>\
                            </div>\
                        </div>\
                    </div>';

                    $('#order-search').append(orderHtml);
                });
            }
        })
    });
</script>
</body>
</html>
