@extends('admin.dashboard')

@section('content')
    <div class="container mx-auto px-4 pt-16">
        <div class="flex items-start">
            <div class="w-2/3 mx-2">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="rounded-lg shadow-md">
            </div>
            <div class="w-1/3 mx-2">
                <h1 class="text-4xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                <div class="mt-4">
                    <span class="text-gray-600">Prix</span>
                    <span class="ml-1 text-lg font-semibold text-gray-800">{{ $product->price }} €</span>
                </div>
                <div class="mt-4">
                    <span class="text-gray-600">Stock</span>
                    <span class="ml-1 text-lg font-semibold text-gray-800">{{ $product->stock }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-gray-600">Catégorie</span>
                    <span class="ml-1 text-lg font-semibold text-gray-800">{{ $product->category->name }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-gray-600">Nombre de commandes</span>
                    <span class="ml-1 text-lg font-semibold text-gray-800">{{ $product->orders->count() }}</span>
                </div>
                <div class="mt-8">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2 border border-blue-500 text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">Retour</a>
                </div>
            </div>
        </div>
    </div>
@endsection
