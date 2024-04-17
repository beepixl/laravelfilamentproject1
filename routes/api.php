<?php

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);


Route::get('brand', [HomeController::class, 'brand']);
Route::get('category', [HomeController::class, 'category']);
Route::get('product', [HomeController::class, 'product']);

Route::get('home', [HomeController::class, 'home']);

Route::post('/new-order',[OrderController::class,'createOrder']);
Route::group(['prefix' => 'product', 'middleware' => 'auth:api'], function () {

    Route::get('category/{category_id}', [ProductController::class, 'getProductsByCategory']);
    Route::get('detail/{product_id}', [ProductController::class, 'getProducts']);

    Route::get('sub-category/{sub_category_id}', [ProductController::class, 'getProductsBySubCategory']);


});