<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\ShopifyProductsController;
use App\Http\Controllers\WebhookController;
use App\Jobs\WebhookProductCreate;
use App\Models\Product;
use App\Models\Shop;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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


//* old project ----------------------------------------------------
// Route::get('/', function () {
//     return redirect()->route('product.index');
// });
// Route::resource('product', ProductController::class);
// Route::POST('approve', [ProductController::class,'approveProduct'])->name('approve');
// Route::get('product/status/{status}',[ProductController::class,'showByStatus'])->name('status');
// Route::get('search',[ProductController::class,'searchProduct'])->name('search');
//* old project ----------------------------------------------------



Route::get('/',[ShopifyController::class,'getShopInput'])->name('shopify');
Route::prefix('shopify')->group(function (){
    Route::get('/',[ShopifyController::class,'getShopInput'])->name('shopify');
    Route::get('/install',[ShopifyController::class,'installApp'])->name('installApp');
    Route::post('/',[ShopifyController::class,'postShopInput'])->name('shopify');
    Route::get('/authenticate',[ShopifyController::class,'authenticate'])->name('authenticate');

    Route::get('/logout',[ShopifyController::class,'logout'])->name('logout');

    Route::prefix('/webhook')->group(function(){
        Route::post('/products/create', [WebhookController::class,'productCreate']);
        Route::post('/products/update', [WebhookController::class,'productUpdate']);
        Route::post('/products/delete', [WebhookController::class,'productDelete']);
    });
});
Route::resource('shopifyProduct', ShopifyProductsController::class)->middleware('verifiedShop');



//* -------------------------------------Test--------------------------------------------------------------
Route::get('/session',function(Request &$request) // Test session
{
    // $request->session()->forget('shop');
    // $shop = session('shop');
    // dump($shop);
})->name('session');

Route::get('/destroyWebhook',[ShopifyController::class,'destroy']);;
Route::get('graph',[ShopifyController::class,'callGraphQL'])->name('graphQL');
