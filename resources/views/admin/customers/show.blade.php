@extends('admin.dashboard')

@section('content')

{{-- bountou retour    --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Client: {{ $customer->firstname }} {{ $customer->lastname }}</h1>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Modifier</a>
    </div>

    {{-- Voir le client et l'historique de ces commandes avec des classes tailwind  --}}
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Details Client</h2>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-4">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Information Personnel
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Nom Complet
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $customer->firstname }} {{ $customer->lastname }}
                        </dd>
                    </div>
                    <!-- Add more details here -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Adresse
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $customer->address }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Téléphone
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $customer->phone }}
                        </dd>
                    </div>
                    {{--  nombre de commandes                  --}}
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Nombre de commandes
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $customer->orders->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-4">Historique des Commandes</h2>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Commandes
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Numéro de commande
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
                    @foreach ($customer->orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $order->numOrder }}</div>
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
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection


