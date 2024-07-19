<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductFormRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    private function extractData(Product $product, ProductFormRequest $request): array
    {
        $data = $request->validated();
        $photo = $request->file('photo');
        if ($photo === null || $photo->getError()) {
            return $data;
        }
        if ($product->photo !== null) {
            Storage::disk('public')->delete($product->photo);
        }
        $data['photo'] = $photo->store('products', 'public');
        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        $product = Product::create($this->extractData(new Product(), $request));
        return response()->json([
            'message' => "Le produit $product->name a été créé avec succès",
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, Product $product)
    {
        $product->update($this->extractData($product, $request));
        return response()->json([
            'message' => "Le produit $product->name a été modifié avec succès",
            'product' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->orders()->count() > 0) {
            return response()->json([
                'error' => "Le produit $product->name est associé à une commande, vous ne pouvez pas le supprimer"
            ], 400);
        }
        if ($product->photo !== null) {
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();
        return response()->json([
            'message' => "Le produit $product->name a été supprimé avec succès"
        ]);
    }

    /**
     * Export products data to an Excel file.
     */
    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    /**
     * Import products data from an Excel file.
     */
    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        Excel::import(new \App\Imports\ProductsImport, $request->file('file'));
        return response()->json([
            'message' => 'Les produits ont été importés avec succès'
        ]);
    }
}
