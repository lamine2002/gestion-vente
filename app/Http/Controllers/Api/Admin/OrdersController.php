<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\OrderFormRequest;
use App\Http\Requests\Admin\SearchOrderRequest;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
    public function __construct()
    {

    $this->authorizeResource(\App\Models\Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SearchOrderRequest $request)
    {

        try {
            $orders = Order::with('customer')->get();
            return response()->json([
                'orders' => $orders,
                'message' => 'Commandes récupérées avec succès.',
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des commandes.',
                'status' => 500

            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderFormRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->input('customer_id'),
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
            'payment' => $request->input('payment'),
            'numOrder' => $request->input('numOrder'),
            'orderDate' => $request->input('orderDate'),
            'total' => $request->input('total')
        ]);

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

        return response()->json([
            'message' => "La commande $order->numOrder a été créée avec succès",
            'order' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderFormRequest $request, Order $order)
    {
        $order->update([
            'customer_id' => $request->input('customer_id'),
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
            'payment' => $request->input('payment'),
            'numOrder' => $request->input('numOrder'),
            'orderDate' => $request->input('orderDate'),
            'total' => $request->input('total')
        ]);

        // Détacher tous les produits de la commande
        $order->products()->detach();

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

        return response()->json([
            'message' => "La commande $order->numOrder a été modifiée avec succès",
            'order' => $order
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->products()->detach();
        $order->delete();

        return response()->json([
            'message' => "La commande $order->numOrder a été supprimée avec succès"
        ], 200);
    }
}
