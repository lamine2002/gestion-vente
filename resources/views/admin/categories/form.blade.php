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
            @include('share.input', ['label' => 'Nom de la Produit', 'name' => 'name', 'value' => old('name', $category->name)])
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
