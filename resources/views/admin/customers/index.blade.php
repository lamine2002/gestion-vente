@extends('admin.dashboard')

@section('title', 'Clients')

@section('content')
    @can('create', \App\Models\Customer::class)
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.customers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter un Client</a>
    </div>
    @endcan
    <div class="mt-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                <th class="px-6 py-3 text-left text-2xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($customers as $customer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->lastname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->firstname }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->address }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->phone }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $customer->sex }}</td>
                    @can('update', $customer)
                    <td class="px-6 py-4 whitespace-nowrap ">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="post" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                        </form>
                    </td>
                    @endcan
                    @cannot('update', $customer)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-red-600">Non autorisé</span>
                    </td>
                    @endcannot
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $customers->links() }}
    </div>


@endsection
