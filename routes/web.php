<?php

use App\Http\Controllers\ProfileController;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
//    \App\Models\User::create(
//        [
//            'name' => 'admin',
//            'email' => 'admin@gmail.com',
//            'password' => \Illuminate\Support\Facades\Hash::make('passer')
//    ]);
//    \App\Models\User::create(
//        [
//            'name' => 'diallo',
//            'email' => 'diallo@gmail.com',
//            'password' => \Illuminate\Support\Facades\Hash::make('passer')
//        ]);

//    $order = \App\Models\Order::find(1);
//    dd($order->getDetailsAttribute());
    return view('welcome');
});

//Route::get('/admin/dashboard', function () {
//    return view('admin.dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $customersCount = Customer::count();
        $maleCustomersCount = Customer::where('sex', 'M')->count();
        $femaleCustomersCount = Customer::where('sex', 'F')->count();
        $productsCount = Product::count();
        $ordersCount = \App\Models\Order::count();
        return view('admin.dashboard',
            compact('customersCount', 'maleCustomersCount', 'femaleCustomersCount', 'productsCount', 'ordersCount')
        );
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

});
Route::get('/products/export', [\App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export')->middleware('auth');
// route vers le formulaire d'importation
Route::get('/products/import', [\App\Http\Controllers\Admin\ProductController::class, 'import'])->name('products.import')->middleware('auth');
// route pour l'importation
Route::post('/products/import', [\App\Http\Controllers\Admin\ProductController::class, 'importData'])->name('products.importData')->middleware('auth');


require __DIR__.'/auth.php';
