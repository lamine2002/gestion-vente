<?php

namespace App\Http\Controllers\Admin;

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
    /**
     * Display a listing of the resource.
     */

    private function extractData (Product $product, ProductFormRequest $request):array {
        $data = $request->validated();
        $photo = $request->validated('photo');
        if ($photo === null || $photo->getError()) {
            return $data;
        }
        if ($product->photo !== null) {
            Storage::disk('public')->delete($product->photo);
        }
        $data['photo'] = $photo->store('products', 'public');
        return $data;
    }
    public function index()
    {
        return view('admin.products.index',
            [
                'products' => \App\Models\Product::orderBy('created_at', 'desc')->paginate(10)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.form',
            [
                'product' => new \App\Models\Product(),
                'categories' => \App\Models\Category::pluck('name', 'id')
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        $product = Product::create($this->extractData(new Product(), $request));
        return redirect()->route('admin.products.index')->with('success', "Le produit $product->name a été créé avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show',
            [
                'product' => $product
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.form',
            [
                'product' => $product,
                'categories' => \App\Models\Category::pluck('name', 'id')
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, Product $product)
    {
        $product->update($this->extractData($product, $request));
        return redirect()->route('admin.products.index')->with('success', "Le produit $product->name a été modifié avec succès");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //supprimer l'image correspondante de l'utilisateur si elle existe
        if ($product->photo !== null) {
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', "Le produit $product->name a été supprimé avec succès");
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

}
