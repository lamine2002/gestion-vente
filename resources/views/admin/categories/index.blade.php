@extends('admin.dashboard')

@section('title', 'Categories')

@section('content')
{{--    <div>{{ Auth::user()->role }}</div>--}}
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter une catégorie</a>
    </div>
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
                <td class="px-6 py-4 whitespace-nowrap">
                    @can('update', $category)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editer</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                            </form>
                        </div>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $categories->links() }}
@endsection
