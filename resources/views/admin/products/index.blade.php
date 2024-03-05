@extends('admin.dashboard')

@section('title', 'Produits')

@section('content')
    @can('create', \App\Models\Product::class)
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter un Produit</a>
        {{-- div pour les bouton d'export et d'import       --}}
        <div class="flex justify-between">
            <a href="{{ route('products.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Exporter</a>
{{--            <a href="{{ route('admin.products.import') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-4">Importer</a>--}}
        </div>
    </div>
    @endcan

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap">
            <thead>
            <tr
                class="text-2xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Categorie</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y">
            @forelse ($products as $product)
                <tr class="text-gray-700">
                    <td class="px-4 py-3">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ substr($product->description, 0, 35)}}</td>
                    <td class="px-4 py-3">{{ $product->price }}</td>
                    <td class="px-4 py-3">{{ $product->stock }}</td>
                    <td class="px-4 py-3">{{ $product->category->name }}</td>
                    @can('update', $product)
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <a href="{{ route('admin.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Voir</a>
                        <form class="inline-block" action="{{ route('admin.products.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                        </form>
                    </td>
                    @endcan
                    @cannot('update', $product)
                    <td class="px-4 py-3">
                        <span class="text-red-600">Non autorisé</span>
                    </td>
                    @endcannot
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-3 text-center" colspan="5">Aucun produit trouve</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class = "mt-8">
        {{ $products->links() }}
    </div>
@endsection
