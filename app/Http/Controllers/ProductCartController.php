<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\ProductOrder;
use TCPDF;

class ProductCartController extends Controller
{
    public function onAddToProductCart(Request $request)
    {
        $product = Product::find($request->id);

        if (!$product) {
            return response()->json([
                'error' => 'Unable to find product',
            ], 404);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'quantity' =>  1,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back();
    }


    public function onProductCart()
    {
        $cart = session()->get('cart');

        if (!$cart) {
            return redirect()->back();
        }

        $products = [];
        foreach (array_keys($cart) as $el) {
            $product = Product::find($el);
            $product->quantity = $cart[$el]['quantity'];
            $products[] = $product;
        }

        return view('cart', ['products' => $products]);
    }

    public function onDeleteCartProduct(Request $request)
    {
        $cart = session()->get('cart');
        $product_id = $request->id;

        if ($cart) {
            if (isset($cart[$product_id])) {
                unset($cart[$product_id]);

                if (empty($cart)) {
                    session()->forget('cart');
                    return redirect()->route('welcome');
                }

                session()->put('cart', $cart);
            }
        }

        return redirect()->back();
    }

    public function onCartOrder()
    {
        $cartProducts = session()->get('cart');

        if (!$cartProducts) {
            return redirect()->back();
        }

        $orderID = uniqid();

        $total_discount = 0;
        $total_price = 0;

        foreach (array_keys($cartProducts) as $el) {
            $quantity = $cartProducts[$el]['quantity'];
            $product = Product::find($el);

            $discount = round(($product->price * $product->discount) / 100) * $quantity;
            $price = round($product->price) * $quantity;

            $total_discount += $discount;
            $total_price += $price;

            ProductOrder::create([
                'user_id' => Auth::user()->id,
                'product_id' => $el,
                'total_discount' => $discount,
                'total_price' => $price,
                'total_quantity' => $quantity,
                'order_id' => $orderID,
            ]);

            $product->available -= $quantity;
            if ($product->available < 0) {
                $product->available = 0;
            }

            $product->save();
        }

        session()->forget('cart');
        return redirect()->route('welcome');
    }
}
