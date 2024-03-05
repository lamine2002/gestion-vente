@extends('admin.dashboard')

@section('title', 'Importer des produits')

@section('content')
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>
    <div class="overflow-x-auto bg-white rounded shadow">
        <form action="{{ route('products.importData') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Fichier Excel:</label>
                <input type="file" name="file" id="file" class="border-2 border-gray-500 rounded py-2 px-3 w-full">
                @error('file')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Importer</button>
        </form>
    </div>
@endsection
