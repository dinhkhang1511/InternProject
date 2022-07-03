<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopifyController;
use App\Models\Shop;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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



Route::get('/', function () {
    return redirect()->route('product.index');
});


Route::resource('product', ProductController::class);
Route::POST('approve', [ProductController::class,'approveProduct'])->name('approve');

Route::get('product/status/{status}',[ProductController::class,'showByStatus'])->name('status');
Route::get('search',[ProductController::class,'searchProduct'])->name('search');

Route::prefix('shopify')->group(function (){
    Route::get('/',[ShopifyController::class,'getShopInput']);
    Route::get('/install',[ShopifyController::class,'installApp'])->name('installApp');
    Route::post('/shop',[ShopifyController::class,'postShopInput'])->name('shopify');

    Route::get('/url',[ShopifyController::class,'generateCode'])->name('url');

});

