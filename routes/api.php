<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//PUBLIC

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//PRIVATE

Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'getUser');
    });

    Route::get('products', [ProductController::class, 'getProducts']);

    Route::middleware([IsAdmin::class])->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::post('products', 'addProduct');
            Route::get('/products/{id}', 'getProductById');
            Route::patch('/products/{id}', 'updateProduct');
            Route::delete('/products/{id}', 'deleteProduct');
        });
    });
});
