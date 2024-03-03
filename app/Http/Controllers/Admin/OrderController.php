<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderFormRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Order::class, 'order');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.orders.index',
            [
                'orders' => \App\Models\Order::orderBy('created_at', 'desc')->paginate(10)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.orders.form',
            [
                'order' => new \App\Models\Order(),
                'customers' => \App\Models\Customer::all('firstname', 'lastname', 'address','phone', 'id'),
                'products' => \App\Models\Product::all('name', 'price', 'stock', 'id'),
                'users' => \App\Models\User::pluck('name', 'id')
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderFormRequest $request)
    {
//        dd($request->validated());
        $order = \App\Models\Order::create(
            [
                'customer_id' => $request->input('customer_id'),
                'user_id' => $request->input('user_id'),
                'status' => $request->input('status'),
                'payment' => $request->input('payment'),
                'numOrder' => $request->input('numOrder'),
                'orderDate' => $request->input('orderDate'),
                'total' => $request->input('total')
            ]
        );
        $orderStatus = $request->input('status');
        $OrderValidate = $orderStatus === 'Terminée';
        // synchroniser les produits et les quantités
        foreach ($request->input('products') as $key => $product) {
            $order->products()->attach($product, ['quantity' => $request->input('quantities')[$key]]);
            // mise à jour du stock
            if ($OrderValidate){
                $productUpdate = \App\Models\Product::find($product);
                if ($productUpdate) {
                    // mise à jour du stock
                    $newStock = $productUpdate->stock - $request->input('quantities')[$key];
                    $productUpdate->update(['stock' => $newStock]);
                }
            }
        }
        return redirect()->route('admin.orders.index')->with('success', "La commande $order->numOrder a été créée avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('admin.orders.show',
            [
                'order' => $order
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.form',
            [
                'order' => $order,
                'customers' => \App\Models\Customer::all('firstname', 'lastname', 'address','phone', 'id'),
                'products' => \App\Models\Product::all('name', 'price', 'stock', 'id'),
                'users' => \App\Models\User::pluck('name', 'id')
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderFormRequest $request, Order $order)
    {
//        dd($request->validated());
        $order->update(
            [
                'customer_id' => $request->input('customer_id'),
                'user_id' => $request->input('user_id'),
                'status' => $request->input('status'),
                'payment' => $request->input('payment'),
                'numOrder' => $request->input('numOrder'),
                'orderDate' => $request->input('orderDate'),
                'total' => $request->input('total')
            ]
        );

        $orderStatus = $request->input('status');
        $OrderValidate = $orderStatus === 'Terminée';
        // synchroniser les produits et les quantités
        foreach ($request->input('products') as $key => $product) {
            $order->products()->syncWithoutDetaching([$product => ['quantity' => $request->input('quantities')[$key]]]);
            // syncWithoutDetaching pour ne pas supprimer les produits qui ne sont pas dans la requête de la base de données
            // mise à jour du stock
            if ($OrderValidate){
                $productUpdate = \App\Models\Product::find($product);
                if ($productUpdate) {
                    // mise à jour du stock
                    $newStock = $productUpdate->stock - $request->input('quantities')[$key];
                    $productUpdate->update(['stock' => $newStock]);
                }
            }
        }

        return redirect()->route('admin.orders.index')->with('success', "La commande $order->numOrder a été modifiée avec succès");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', "La commande $order->numOrder a été supprimée avec succès");
    }
}
