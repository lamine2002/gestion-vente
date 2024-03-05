<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @yield('title', "Dashboard")
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(request()->routeIs('admin.dashboard') && auth()->user()->role == 'admin')
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Nombre de clients -->
                            <div class="p-6 bg-blue-400 h-48  text-white rounded-lg shadow">
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold">{{ $customersCount }}</h2>
                                    <p>Nombre total de clients</p>
                                </div>
                            </div>

                            <!-- Nombre de clients par sexe -->
                            <div class="p-6 bg-green-400 h-48  text-white rounded-lg shadow">
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold">{{ $maleCustomersCount }}</h2>
                                    <p>Nombre de clients masculins</p>
                                </div>
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold">{{ $femaleCustomersCount }}</h2>
                                    <p>Nombre de clients féminins</p>
                                </div>
                            </div>

                            <!-- Nombre de produits -->
                            <div class="p-6 bg-red-400 h-48  text-white rounded-lg shadow">
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold">{{ $productsCount }}</h2>
                                    <p>Nombre total de produits</p>
                                </div>
                            </div>

                            <!-- Autres statistiques importantes -->
                            <!-- Par exemple, le nombre total de commandes -->
                            <div class="p-6 bg-yellow-400 h-48  text-white rounded-lg shadow">
                                <div class="text-center">
                                    <h2 class="text-2xl font-bold">{{ $ordersCount }}</h2>
                                    <p>Nombre total de commandes</p>
                                </div>
                            </div>

                            <!-- Ajoutez ici d'autres statistiques intéressantes -->
                        </div>
                    @endif
                    @include('share.flash')
                    <br>
                    @yield('content')

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
