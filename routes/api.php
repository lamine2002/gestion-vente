<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;

//use App\Http\Controllers\Ecomm\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Routes pour l'authentification
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('orders', [OrderController::class, 'index'])->name('api.admin.orders.index');

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('api.admin.products.index');
    Route::post('products', [ProductController::class, 'store'])->name('api.admin.products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('api.admin.products.show');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('api.admin.products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('api.admin.products.destroy');
    Route::get('products/export', [ProductController::class, 'export'])->name('api.admin.products.export');
    Route::post('products/import', [ProductController::class, 'importData'])->name('api.admin.products.import');


    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('api.admin.customers.show');

    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('api.admin.customers.destroy');

    Route::post('orders', [OrderController::class, 'store'])->name('api.admin.orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('api.admin.orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('api.admin.orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('api.admin.orders.destroy');

});

Route::get('/categories', [CategoryController::class, 'index'])->name('api.admin.categories.index');
Route::post('/store/categories', [CategoryController::class, 'store'])->name('api.admin.categories.store');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('api.admin.categories.show');
Route::put('categories/{category}', [CategoryController::class, 'update'])->name('api.admin.categories.update');
Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('api.admin.categories.destroy');
Route::get('customers', [CustomerController::class, 'index'])->name('api.admin.customers.index');
Route::post('/store/customers', [CustomerController::class, 'store'])->name('api.admin.customers.store');
Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('api.admin.customers.update');



Route::get('/products', [App\Http\Controllers\Ecomm\ProductController::class, 'homeProducts']);
Route::get('/product/{id}', [App\Http\Controllers\Ecomm\ProductController::class, 'productDetail']);
Route::post('/make-order', [App\Http\Controllers\Ecomm\OrderController::class, 'makeOrder']);
Route::get('/track-order/{numOrder}', [App\Http\Controllers\Ecomm\OrderController::class, 'trackOrder']);

