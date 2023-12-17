<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\ProductOrder;

class AdminController extends Controller
{
    public function addProduct(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:255',
                'available' => 'required|integer',
                'discount' => 'nullable|numeric',
                'price' => 'required|numeric',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $product = Product::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'category' => $request->input('category'),
                'available' => $request->input('available'),
                'discount' => $request->input('discount'),
                'price' => $request->input('price'),
                'created_by' => Auth::id(),
            ]);

            $productImageFolder = public_path('assets/' . $product->id);
            if (!file_exists($productImageFolder)) {
                mkdir($productImageFolder, 0755, true);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = Str::random(20) . '.' . $image->getClientOriginalExtension();
                    $image->move($productImageFolder, $imageName);
                }
            }

            return redirect()->route('admin.home');
        }

        return view('admin.add_product');
    }

    public function onDeleteProduct($id) {
        $product = Product::find($id);

        if ($product) {
            $dir = public_path('assets' . DIRECTORY_SEPARATOR . $product->id);
            if (File::exists($dir)) {
                File::deleteDirectory($dir);
            }

            Product::where('id', $id)->delete();
        }

        return redirect()->route('admin.home');
    }

    public function onEditProduct($id) {
        $product = Product::find($id);

        return view('admin.edit', ['product' => $product]);
    }

    public function onEditedProduct(Request $request) {
        $product = Product::find(request('id'));

        if ($product && $request->isMethod('post')) {
            $product->title = $request->input('title');
            $product->content = $request->input('content');
            $product->category = $request->input('category');
            $product->available = $request->input('available');
            $product->discount = $request->input('discount');
            $product->price = $request->input('price');
            $product->save();
    
            if ($request->hasFile('images')) {
                $imageFolder = public_path('assets/' . $product->id);
                if (!File::isDirectory($imageFolder)) {
                    File::makeDirectory($imageFolder, 0755, true, true);
                }
    
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move($imageFolder, $imageName);
                }
            }
        }

        return redirect()->route('admin.home');
    }

    
    public function onDeleteImage($id, $name) {
        $postImageFolder = public_path('assets/' . $id);
        $imagePath = $postImageFolder . DIRECTORY_SEPARATOR . $name;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return redirect()->back();
    }

    public function onAdminStatistic() {
        $groupsData = DB::table('product_likes')
            ->select('is_liked', DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as total'), 'product_id')
            ->where('is_liked', 1) 
            ->groupBy('is_liked', 'date', 'product_id')
            ->get();
        
        $products = Product::where('created_by', Auth::user()->id)->pluck('id')->toArray();
        $likesByDate = [];
        
        foreach ($groupsData as $data) {
            if (in_array($data->product_id, $products)) {
                if (!isset($likesByDate[$data->date])) {
                    $likesByDate[$data->date] = 0;
                }
                $likesByDate[$data->date] += $data->total;
            }
        }
        
        $dates = array_keys($likesByDate);
        
        $colours = [];
        foreach ($dates as $date) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }
        
        $adminId = Auth::user()->id;
        $ordersData = DB::table('product_order')
            ->select(DB::raw('DATE(product_order.created_at) as date'), DB::raw('count(*) as total'))
            ->join('products as p', 'product_order.product_id', '=', 'p.id')
            ->where('p.created_by', $adminId)
            ->groupBy('date')
            ->get();
    
        $ordersByDate = [];
        foreach ($ordersData as $data) {
            $ordersByDate[$data->date] = $data->total;
        }
    
        $orderDates = array_keys($ordersByDate);
    
        $orderColours = [];
        foreach ($orderDates as $date) {
            $orderColours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }
    
        return view('admin.statistic', [
            'chart' => compact('likesByDate', 'dates', 'ordersByDate', 'orderDates'),
            'colours' => $colours,
            'orderColours' => $orderColours,
        ]);
    }    

    public function onAdminOrders(Request $request) {
        $adminProducts = Product::where('created_by', Auth::user()->id)->pluck('id')->toArray();
    
        $orderedProducts = [];
        $orders = ProductOrder::whereIn('product_id', $adminProducts)->get();
    
        if ($request->has('status')) {
            $status = $request->status;
            $filteredOrders = $orders->filter(function ($order) use ($status) {
                return $order->status === $status;
            });
    
            foreach ($filteredOrders as $order) {
                $orderedProducts[$order->order_id][] = $order;
            }
        } else {
            foreach ($orders as $order) {
                $orderedProducts[$order->order_id][] = $order;
            }
        }
    
        return view('admin.orders', ['products' => $orderedProducts]);
    }
    

    public function onAdminUpdateStatus(Request $request) {
        $orders = ProductOrder::where(['order_id' => $request->id])->get();
    
        foreach($orders as $order) {
            $order->status = $request->status;
            $order->save();
        }
    
        return redirect()->back();
    }
}
