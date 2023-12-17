<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Support\Facades\DB;

class DetailsController extends Controller
{
    public function onDetails($id) {
        $product = Product::find($id);

        if ($product) {
            $productFolder = public_path('assets/' . $product->id);

            if (is_dir($productFolder)) {
                $images = scandir($productFolder);
                $imageTags = [];
    
                foreach ($images as $image) {
                    if (!in_array($image, ['.', '..']) && is_file($productFolder . '/' . $image)) {
                        $imageTags[] = '<img src="' . asset('assets/' . $product->id . '/' . $image) . '" style="height: 400px;"/>';
                    }
                }
            }

            return view('details', ['product' => $product, 'images' => $imageTags]);
        } else {
            return redirect()->back();
        }
    }
}
