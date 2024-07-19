<?php

namespace App\Http\Controllers\Ecomm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderFormRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function makeOrder(OrderFormRequest $request)
    {
        try {

            // vérification de l'existence du client
            $customer = Customer::find($request->customer_id);
            if (!$customer) {
                $customer = Customer::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'sex' => $request->sex
                ]);

            }

            $order = new Order();
            $order->numOrder = 'ORD' . time() . rand(1000, 9999);
            $order->orderDate = now();
            $order->status = 'en attente';
            $order->total = $request->total;
            if ($customer){
                $order->customer_id = $customer->id;
            }
            $order->payment = $request->payment;
            $order->save();
            foreach ($request->input('products') as $key => $product) {
                $order->products()->attach($product, ['quantity' => $request->input('quantities')[$key]]);
                // mise à jour du stock
                $productUpdate = \App\Models\Product::find($product);
                if ($productUpdate) {
                    // mise à jour du stock
                    $newStock = $productUpdate->stock - $request->input('quantities')[$key];
                    $productUpdate->update(['stock' => $newStock]);
                }

            }
            return response()->json([
                'message' => 'Commande enregistrée avec succès',
                'order' => $order,
                'status' => 200
            ]);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Erreur lors de l\'enregistrement de la commande',
                'status' => 500
            ]);
        }
    }
}
