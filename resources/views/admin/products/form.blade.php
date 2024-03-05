@extends('admin.dashboard')

@section('title', $product->exists ? "Éditer un produit" : "Créer un produit")

@section('content')
    {{-- Ajout d'un bouton de retour --}}
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>

    <form class="space-y-6" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
          method="post" enctype="multipart/form-data">

        @csrf
        @if($product->exists)
            @method('patch')
        @endif

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            @include('share.input', ['label' => 'Nom de la Produit', 'name' => 'name', 'value' => old('name', $product->name)])
            @include('share.input', ['label' => 'Prix', 'name' => 'price', 'value' => old('price', $product->price)])
            @include('share.input', ['label' => 'Stock', 'name' => 'stock', 'value' => old('stock', $product->stock)])
            @include('share.select', ['label' => 'Catégorie', 'name' => 'category_id', 'options' => $categories, 'value' => old('category_id', $product->category_id)])
        </div>
        @include('share.input', ['label' => 'Description', 'type' =>'textarea', 'name' => 'description', 'value' => old('description', $product->description)])

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">
                Image
            </label>
            <div class="mt-1 flex items-center">
            <span class="inline-block h-48 w-48 overflow-hidden rounded-full">
                <img class="h-48 w-48 rounded-full" src="{{ $product->photo ? $product->imageUrl() : asset('images/default.png') }}" alt=""/>
            </span>
                <input type="file" name="photo" id="image" class="ml-5 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            @error('photo')
            <div class="text-red-500 mt-1 text-sm">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ $product->exists ? "Modifier" : "Ajouter" }}
            </button>
        </div>

    </form>

@endsection
