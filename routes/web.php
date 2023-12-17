<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\LikedProductsController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\OrderDetailsController;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Main page
Route::get('/', [HomeController::class, 'home'])->name('welcome');
Route::post('/', [HomeController::class, 'onSearch'])->name('search');

// Details
Route::get('/details/{id}', [DetailsController::class, 'onDetails'])->name('details');
Route::get('/like/{user_id}/{product_id}', [LikedProductsController::class, 'onProductLike'])->name('product-like');

// Like
Route::get('/liked', [LikedProductsController::class, 'onLikedProducts'])->name('liked');

// Cart
Route::get('/cart/add/{id}', [ProductCartController::class, 'onAddToProductCart'])->name('cart.add');
Route::get('/cart', [ProductCartController::class, 'onProductCart'])->name('cart');
Route::get('/cart/delete/{id}', [ProductCartController::class, 'onDeleteCartProduct'])->name('cart.delete');
Route::get('/cart/order', [ProductCartController::class, 'onCartOrder'])->name('cart.order');

// Orders
Route::get('/orders', [OrderDetailsController::class, 'onOrders'])->name('orders');
Route::post('/orders', [OrderDetailsController::class, 'onSearchOrder'])->name('orders.search');
Route::get('/orders/detail/{id}', [OrderDetailsController::class, 'onOrderDetails'])->name('orders.detail');

Auth::routes();

Route::group(['middleware' => ['is_admin']], function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

    Route::get('/admin/add_product', [AdminController::class, 'addProduct'])->name('admin.add_product');
    Route::post('/admin/add_product', [AdminController::class, 'addProduct']);

    Route::get('/admin/delete/{id}', [AdminController::class, 'onDeleteProduct']);

    Route::get('/admin/edit/{id}', [AdminController::class, 'onEditProduct'])->name('admin.edit');
    Route::get('/admin/delete-image/{id}/{image}', [AdminController::class, 'onDeleteImage'])->name('admin.deleteImage');
    Route::post('/admin/edit/{id}', [AdminController::class, 'onEditedProduct'])->name('admin.edit_product');

    Route::get('admin/statistic', [AdminController::class, 'onAdminStatistic'])->name('admin.statistic');

    Route::get('admin/orders', [AdminController::class, 'onAdminOrders'])->name('admin.orders');
    Route::get('admin/orders/update/{id}/{status}', [AdminController::class, 'onAdminUpdateStatus'])->name('admin.update.status');
});


Route::group(['middleware' => ['is_owner']], function () {
    Route::get('/owner/home', [HomeController::class, 'ownerHome'])->name('owner.home');

    Route::get('/owner/home/delete/{id}', [HomeController::class, 'onOwnerDeleteUser'])->name('owner.delete');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');