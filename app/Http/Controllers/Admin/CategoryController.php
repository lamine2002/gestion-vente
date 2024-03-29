<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        if (Auth::user()->cannot('create', Category::class)) {
//            abort(403);
//        }
        return view('admin.categories.index',
            [
                'categories' => \App\Models\Category::orderBy('created_at', 'desc')->paginate(25)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.form',
            [
                'category' => new \App\Models\Category()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request)
    {
        $category = Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', "La catégorie $category->name a été créée avec succès");
    }

    /**
     * Display the specified resource.
     */
//    public function show(string $id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.form',
            [
                'category' => $category
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryFormRequest $request, Category $category)
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', "La catégorie $category->name a été modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // on ne peut pas supprimer une catégorie qui a des produits
        if ($category->products->isNotEmpty()) {
            return redirect()->route('admin.categories.index')->with('error', "La catégorie $category->name ne peut pas être supprimée car elle contient des produits");
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', "La catégorie $category->name a été supprimée avec succès");
    }
}
