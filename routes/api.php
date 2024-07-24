<?php
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\OrdersController;
use App\Http\Controllers\Api\Admin\ProductsController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
//use App\Http\Controllers\Ecomm\ProductsController;
use App\Models\Customer;
use App\Models\Product;
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

Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
//
Route::middleware('auth:sanctum')->group(function () {
// Route pour créer un nouvel utilisateur (POST)
    Route::post('admin/users', [UserController::class, 'store']);
// Route pour afficher un utilisateur spécifique (GET)
    Route::get('admin/users/{user}', [UserController::class, 'show']);
// Route pour mettre à jour un utilisateur spécifique (PUT/PATCH)
    Route::put('admin/users/{user}', [UserController::class, 'update']);
    Route::patch('admin/users/{user}', [UserController::class, 'update']);
Route::get('orders', [OrdersController::class, 'index'])->name('api.admin.orders.index');
// Route pour supprimer un utilisateur spécifique (DELETE)
    Route::delete('admin/users/{user}', [UserController::class, 'destroy']);
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.admin.categories.index');
//Route::get('/products', [ProductsController::class, 'homeProducts']);
    Route::get('/products', [ProductsController::class, 'index']);
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('api.admin.customers.destroy');
    Route::get('products', [ProductsController::class, 'index'])->name('api.admin.products.index');
    Route::post('store/products', [ProductsController::class, 'store'])->name('api.admin.products.store');
    Route::get('products/{product}', [ProductsController::class, 'show'])->name('api.admin.products.show');
    Route::put('products/{product}', [ProductsController::class, 'update'])->name('api.admin.products.update');
    Route::delete('products/{product}', [ProductsController::class, 'destroy'])->name('api.admin.products.destroy');
    Route::get('products/export', [ProductsController::class, 'export'])->name('api.admin.products.export');
    Route::post('products/import', [ProductsController::class, 'importData'])->name('api.admin.products.import');

    Route::post('/store/categories', [CategoryController::class, 'store'])->name('api.admin.categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('api.admin.categories.show');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('api.admin.categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('api.admin.categories.destroy');
    Route::get('customers', [CustomerController::class, 'index'])->name('api.admin.customers.index');
    Route::post('/store/customers', [CustomerController::class, 'store'])->name('api.admin.customers.store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('api.admin.customers.update');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('api.admin.customers.show');

    Route::get('/admin/dashboard-stats', function () {
        return response()->json([
            'customersCount' => Customer::count(),
            'maleCustomersCount' => Customer::where('sex', 'M')->count(),
            'femaleCustomersCount' => Customer::where('sex', 'F')->count(),
            'productsCount' => Product::count(),
            'ordersCount' => \App\Models\Order::count(),
        ]);
    });
//    Route::get('orders', [OrderController::class, 'index'])->name('api.admin.orders.index');
    Route::post('store/orders', [OrdersController::class, 'store'])->name('api.admin.orders.store');


    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('api.admin.customers.destroy');

//    Route::post('orders', [OrderController::class, 'store'])->name('api.admin.orders.store');

    Route::get('orders/{order}', [OrdersController::class, 'show'])->name('api.admin.orders.show');
    Route::put('orders/{order}', [OrdersController::class, 'update'])->name('api.admin.orders.update');
    Route::delete('orders/{order}', [OrdersController::class, 'destroy'])->name('api.admin.orders.destroy');
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Route pour afficher la liste des utilisateurs (GET)
Route::get('admin/users', [UserController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index'])->name('api.admin.categories.index');
//Route::post('/store/categories', [CategoryController::class, 'store'])->name('api.admin.categories.store');
//Route::get('categories/{category}', [CategoryController::class, 'show'])->name('api.admin.categories.show');
Route::put('categories/{category}', [CategoryController::class, 'update'])->name('api.admin.categories.update');
Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('api.admin.categories.destroy');
Route::get('customers', [CustomerController::class, 'index'])->name('api.admin.customers.index');
Route::post('/store/customers', [CustomerController::class, 'store'])->name('api.admin.customers.store');
Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('api.admin.customers.update');



Route::get('/products', [App\Http\Controllers\Ecomm\ProductController::class, 'homeProducts']);
Route::get('/product/{id}', [App\Http\Controllers\Ecomm\ProductController::class, 'productDetail']);
Route::post('/make-order', [App\Http\Controllers\Ecomm\OrderController::class, 'makeOrder']);
Route::get('/track-order/{numOrder}', [App\Http\Controllers\Ecomm\OrderController::class, 'trackOrder']);


