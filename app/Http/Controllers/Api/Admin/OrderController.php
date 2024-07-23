<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderFormRequest;
use App\Http\Requests\Admin\SearchOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
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
        $query = Order::query()->orderBy('created_at', 'desc');
        if ($customerName = $request->validated('customer_name')) {
            $query = $query->whereHas('customer', function ($query) use ($customerName) {
                $query->where('firstname', 'like', "%$customerName%")
                    ->orWhere('lastname', 'like', "%$customerName%");
            });
        }
        if ($orderNumber = $request->validated('order_number')) {
            $query = $query->where('numOrder', 'like', "%$orderNumber%");
        }
        if ($orderStatus = $request->validated('order_status')) {
            $query = $query->where('status', 'like', "%$orderStatus%");
        }
        if ($orderDate = $request->validated('order_date')) {
            $query = $query->where('orderDate', 'like', "%$orderDate%");
        }
        return response()->json();
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
