<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.add_product');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'available' => 'required|integer',
            'discount' => 'numeric',
            'price' => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.home')->with('success', 'Product added successfully');
    }
}
