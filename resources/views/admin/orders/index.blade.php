@extends('admin.dashboard')

@section('title', 'Commandes')

@section('content')

    <div class="bg-gray-100 p-4 mb-8 text-center rounded-3xl">
        <form action="" method="get" class="container flex gap-2 justify-center">
            <input type="text" placeholder="Numero commande" class="form-input text-sm rounded-2xl" name="order_number" value="{{ $input['order_number'] ?? '' }}">
            <input type="text" placeholder="Nom du Client" class="form-input text-sm rounded-2xl" name="customer_name" value="{{ $input['customer_name'] ?? '' }}">
            <select name="order_status" class="form-select text-sm rounded-2xl">
                <option value="">Tous les status</option>
                <option value="En attente" {{ isset($input['order_status']) && $input['order_status'] == 'En attente' ? 'selected' : '' }}>En attente</option>
                <option value="En cours de traitement" {{ isset($input['order_status']) && $input['order_status'] == 'En cours de traitement' ? 'selected' : '' }}>En cours de traitement</option>
                <option value="Terminée" {{ isset($input['order_status']) && $input['order_status'] == 'Terminée' ? 'selected' : '' }}>Terminée</option>
                <option value="Annulée" {{ isset($input['order_status']) && $input['order_status'] == 'Annulée' ? 'selected' : '' }}>Annulée</option>
            </select>
            <input type="date" class=" form-input text-sm rounded-3xl" name="order_date" value="{{ $input['order_date'] ?? '' }}">
            <button class="btn btn-primary btn-sm flex-grow-0 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 text-xs rounded">
                Rechercher
            </button>
        </form>
    </div>


    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.orders.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter une Commande</a>
    </div>



{{-- de joli card et des couleurs en fonctions du status de la commande     --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Numéro de commande
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>

                                <th class="px-6 py-3 bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                @can('view', $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $order->numOrder }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">{{ \App\Models\Customer::find($order->customer_id)->firstname.' '.\App\Models\Customer::find($order->customer_id)->lastname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                @if($order->created_at)
                                                    {{ $order->created_at->format('d/m/Y à H:i') }}
                                                @else
                                                    Date de création non disponible
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                @if($order->status == 'En attente')
                                                    <span class="bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs">En attente</span>
                                                @elseif($order->status == 'En cours de traitement')
                                                    <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">En cours de traitement</span>
                                                @elseif($order->status == 'Terminée')
                                                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Terminée</span>
                                                @elseif($order->status == 'Annulée')
                                                    <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Annulée</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Voir</a>
                                            @can('update', $order)
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Modifier</a>
                                                <form class="inline-block" action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?');">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endcan
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



    <div class="mt-4">
        {{ $orders->links() }}
    </div>

@endsection
