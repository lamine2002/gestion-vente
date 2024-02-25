@extends('admin.dashboard')

@section('title', $product->exists ? "Editer un produit" : "Créer un produit")

@section('content')

    <form class="space-y-6" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="post">

        @csrf
        @if($product->exists)
            @method('patch')
        @endif

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            @include('share.input', ['label' => 'Nom de la Produit', 'name' => 'name', 'value' => old('name', $product->name)])
            @include('share.input', ['label' => 'Prix', 'name' => 'price', 'value' => old('price', $product->price)])
            @include('share.input', ['label' => 'Stock', 'name' => 'stock', 'value' => old('stock', $product->stock)])
            @include('share.select', ['label' => 'Categorie', 'name' => 'category_id', 'options' => $categories, 'value' => old('category_id', $product->category_id)])
        </div>
        @include('share.input', ['label' => 'Description','type' =>'textarea', 'name' => 'description', 'value' => old('description', $product->description)])

        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                @if($product->exists)
                    Modifier
                @else
                    Créer
                @endif
            </button>
        </div>

    </form>

@endsection
