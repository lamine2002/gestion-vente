<?php

namespace App\Http\Controllers\Ecomm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderFormRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\SmsService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $smsService;
    protected $twilioService;

   /* public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }*/

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    public function makeOrder(OrderFormRequest $request)
    {
        try {

            // vérification de l'existence du client
            $customer = Customer::where('phone', $request->phone)->first();
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
            $order->status = 'En attente';
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

            // Envoi de SMS avec infobip
            $message = "Bonjour {$customer->firstname} {$customer->lastname}, Votre commande {$order->numOrder} a été enregistrée avec succès. Merci pour votre achat!";
            $this->smsService->sendSms($customer->phone, $message);

            // Envoi de SMS avec Twilio
//            $message = "Votre commande {$order->numOrder} a été enregistrée avec succès. Merci pour votre achat!";
//            $this->twilioService->sendSms($customer->phone, $message);

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


    public function trackOrder($numOrder)
    {
        try {
            $order = Order::with('products')->where('numOrder', $numOrder)->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Commande non trouvée',
                    'status' => 404
                ]);
            }

            return response()->json([
                'message' => 'Commande trouvée',
                'order' => $order,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la recherche de la commande: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}
