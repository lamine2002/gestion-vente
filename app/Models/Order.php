<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // recuperer les details de la commande dans la table pivot product_order
    public function getDetailsAttribute()
    {
        $details = [];
        foreach ($this->products as $product) {
            $details[] = [
                'productName' => $product->name,
                'productId' => $product->id,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'total' => $product->price * $product->pivot->quantity
            ];
        }
        return $details;
    }

}
