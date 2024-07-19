<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryFormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json([
            'message' => "La catégorie $category->name a été créée avec succès",
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryFormRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json([
            'message' => "La catégorie $category->name a été modifiée avec succès",
            'category' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // on ne peut pas supprimer une catégorie qui a des produits
        if ($category->products->isNotEmpty()) {
            return response()->json([
                'message' => "La catégorie $category->name ne peut pas être supprimée car elle contient des produits"
            ], 400);
        }

        $category->delete();
        return response()->json([
            'message' => "La catégorie $category->name a été supprimée avec succès"
        ]);
    }
}
