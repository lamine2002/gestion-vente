@extends('admin.dashboard')

@section('title', $category->exists ? "Editer une catégorie" : "Créer une catégorie")

@section('content')

    <form class="space-y-6" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          method="post">

        @csrf
        @if($category->exists)
            @method('patch')
        @endif

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la Categorie</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

        </div>

        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                @if($category->exists)
                    Modifier
                @else
                    Créer
                @endif
            </button>
        </div>

    </form>

@endsection
