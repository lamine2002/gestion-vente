<?php

namespace App\Http\Controllers\Ecomm;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function homeProducts()
    {
        try {
            $products = Product::with('category')->orderBy('created_at', 'desc')->limit(16)->get();
            return response()->json([
                'message' => 'Produits récupérés avec succès',
                'products' => $products,
                'status' => 200
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des produits',
                'status' => 500
            ]);
        }
    }
}
