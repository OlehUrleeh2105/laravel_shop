<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductLike;

use Illuminate\Support\Facades\Auth;

class LikedProductsController extends Controller
{
    public function onProductLike(Request $request) {
        $like = ProductLike::where([
            'user_id' => request('user_id'),
            'product_id' => request('product_id')
        ])->first();

        if ($like) {
            if ($like->is_liked === 1) {
                $like->update(['is_liked' => 0]);
            } else {
                $like->update(['is_liked' => 1]);
            }

            return redirect()->back();
        } else {
            ProductLike::create([
                'user_id' => request('user_id'),
                'product_id' => request('product_id'),
                'is_liked' => 1
            ]);

            return redirect()->back();
        }
    }

    public function onLikedProducts()
    {
        $likedProductIds = ProductLike::where(['user_id' => Auth::user()->id, 'is_liked' => 1])->pluck('product_id')->toArray();

        if (empty($likedProductIds)) {
            return redirect()->back();
        }

        $products = Product::whereIn('id', $likedProductIds)->get();

        return view('liked', ['products' => $products]);
    }
}
