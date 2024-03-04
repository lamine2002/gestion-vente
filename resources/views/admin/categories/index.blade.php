@extends('admin.dashboard')

@section('title', 'Categories')

@section('content')
{{--    <div>{{ Auth::user()->role }}</div>--}}
@can('create', \App\Models\Category::class)
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter une catégorie</a>
    </div>
@endcan
    <br>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-2xs font-bold text-gray-500 uppercase tracking-wider">Nom</th>
            <th scope="col" class="px-6 py-3 text-left text-2xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($categories as $category)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                @can('update', $category)
                <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="text-indigo-600 hover:text-indigo-900">Editer</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                            </form>
                        </div>

                </td>
                @endcan
                @cannot('update', $category)
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-red-600">Non autorisé</span>
                </td>
                @endcannot
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
@endsection
