<?php

use App\Http\Controllers\ProductController;
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
