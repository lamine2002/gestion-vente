<?php
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
//use App\Http\Controllers\Ecomm\ProductController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//
Route::middleware('auth:sanctum')->group(function () {
// Route pour créer un nouvel utilisateur (POST)
    Route::post('admin/users', [UserController::class, 'store']);
// Route pour afficher un utilisateur spécifique (GET)
    Route::get('admin/users/{user}', [UserController::class, 'show']);
// Route pour mettre à jour un utilisateur spécifique (PUT/PATCH)
    Route::put('admin/users/{user}', [UserController::class, 'update']);
    Route::patch('admin/users/{user}', [UserController::class, 'update']);

// Route pour supprimer un utilisateur spécifique (DELETE)
    Route::delete('admin/users/{user}', [UserController::class, 'destroy']);
    Route::get('/categories', [CategoryController::class, 'index'])->name('api.admin.categories.index');
//Route::get('/products', [ProductController::class, 'homeProducts']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('api.admin.customers.destroy');
    Route::get('products', [ProductController::class, 'index'])->name('api.admin.products.index');
    Route::post('store/products', [ProductController::class, 'store'])->name('api.admin.products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('api.admin.products.show');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('api.admin.products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('api.admin.products.destroy');
    Route::get('products/export', [ProductController::class, 'export'])->name('api.admin.products.export');
    Route::post('products/import', [ProductController::class, 'importData'])->name('api.admin.products.import');

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
    Route::get('orders', [OrderController::class, 'index'])->name('api.admin.orders.index');
    Route::post('store/orders', [OrderController::class, 'store'])->name('api.admin.orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('api.admin.orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('api.admin.orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('api.admin.orders.destroy');
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Route pour afficher la liste des utilisateurs (GET)
Route::get('admin/users', [UserController::class, 'index']);
