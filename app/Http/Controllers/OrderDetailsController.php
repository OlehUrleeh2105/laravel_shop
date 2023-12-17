<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\ProductOrder;
use App\Models\Product;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class OrderDetailsController extends Controller
{
    public function onOrders() {
        $orderIDs = DB::table('product_order')
            ->select('order_id')
            ->where('user_id', Auth::user()->id)
            ->groupBy('order_id')
            ->get();

        if(count($orderIDs) === 0) {
            return redirect()->back();
        }
        
        $orders = [];
        foreach($orderIDs as $id) {
            $orders[$id->order_id] = ProductOrder::where('order_id', $id->order_id)->get();
        }
    
        return view('orders', ['ordersMap' => $orders]);
    }

    public function onSearchOrder(Request $request){
        $orders = ProductOrder::where('order_id', 'like', '%' . $request->get('searchQuest') . '%')
            ->where('user_id', Auth::user()->id) 
            ->orderBy('created_at', 'desc')
            ->get();

        $products = [];
        foreach($orders as $order) {
            $product = Product::find($order->product_id);
            
            $product->quantity = $order->total_quantity;
            $product->total_price = $order->total_price;
            $product->total_discount = $order->total_discount;
            $product->order_id = $order->order_id;

            $products[] = $product;
        }
        
        $orderIDs = DB::table('product_order')
            ->select('order_id')
            ->where('order_id', 'like', '%' . $request->get('searchQuest') . '%')
            ->where('user_id', Auth::user()->id)
            ->groupBy('order_id')
            ->get();

        return response()->json(['orders' => $orderIDs,'products' => $products]);
    }

    public function onOrderDetails(Request $request) {
        $order_id = $request->id;
        $orders = ProductOrder::where('order_id', $order_id)->get();
        
        $products = [];
        foreach($orders as $order) {
            $products[] = Product::find($order->product_id);
        }
    
        return view('order_details', ['products' => $products, 'order_id' => $order_id]);
    }    
   
}
