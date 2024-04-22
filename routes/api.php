<?php

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);


Route::get('/home', [HomeController::class, 'home'])->middleware('auth:api');


Route::group(['prefix' => 'product', 'middleware' => 'auth:api'], function () {
    Route::get('/category/{category_id}', [ProductController::class, 'getProductsByCategory']);
    Route::get('/detail/{product_id}', [ProductController::class, 'getProducts']);
    Route::get('/sub-category/{sub_category_id}', [ProductController::class, 'getProductsBySubCategory']);
});

Route::group(['prefix' => 'cart', 'middleware' => 'auth:api'], function () {

    Route::post('/new', [OrderController::class, 'createCart']);
    Route::post('/get-list', [OrderController::class, 'getcart']);
    Route::post('/remove', [OrderController::class, 'remove']);
    Route::post('/new-order', [OrderController::class, 'newOrder']);
});
