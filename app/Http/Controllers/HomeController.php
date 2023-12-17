<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function adminHome()
    {
        $products = DB::table('products')
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->simplePaginate(3);

        return view('admin.home', ['products' => $products]);
    }

    public function ownerHome() {
        $users = User::where([
            'is_admin' => 0,
            'is_owner' => 0
        ])->get();

        return view('owner.home', ['users' => $users]);
    }

    public function onOwnerDeleteUser(Request $request) {
        $user = User::find($request->id);

        if($user) {
            $user->delete();
        }

        return redirect()->back();
    }

    public function onSearch(Request $request) {
        $products = Product::where('title', 'like', '%' . $request->get('searchQuest') . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach($products as $product) {
            $postImageFolder = public_path('assets/' . $product->id);
            $imagePath = null;

            if (is_dir($postImageFolder)) {
                $images = scandir($postImageFolder);

                foreach ($images as $image) {
                    if (!in_array($image, ['.', '..'])) {
                        $product->images = asset('assets/' . $product->id . '/' . $image);
                        break;
                    }
                }
            }

            $product->user_id = Auth::user()->id;

            $like = ProductLike::where([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id
            ])->first();

            $product->liked = $like ? ($like->is_liked === 1 ? "btn-primary bg-primary" : "btn-outline-primary") : "btn-outline-primary";
        }

        return json_encode($products);
    }

    public function home(Request $request) {
        $query = DB::table('products')->orderBy('created_at', 'desc');
        $selectedCategory = $request->input('category');

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $products = $query->simplePaginate(3);
        return view('welcome', ['products' => $products]);
    }
}
