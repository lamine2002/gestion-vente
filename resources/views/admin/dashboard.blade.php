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
                            <div class="container mx-auto px-4 ml-64">
                                <h1 class="text-3xl font-bold mb-4 text-center">Graphique</h1>
                                {{--<div class="flex flex-wrap -mx-2">
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <div class="bg-green-500 text-white rounded shadow">
                                            <div class="p-4">
                                                <h5 class="text-xl font-bold">Nombre total de clients <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                                    </svg></h5>
                                                <p class="card-text">{{ $customersCount }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 px-2 mb-4">
                                        <div class="bg-red-500 text-white rounded shadow">
                                            <div class="p-4">
                                                <h5 class="text-xl font-bold">Nombre total de produits <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                                                        <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z"/>
                                                    </svg></h5>
                                                <p class="card-text">{{ $productsCount }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="flex flex-wrap -mx-2 mt-4">
                                    <div class="w-full px-2">
                                        <div class="bg-white rounded shadow">
                                            <div class="p-4">
                                                <h5 class="text-xl font-bold">Répartition des clients par sexe</h5>
                                                <canvas id="chartSexe"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                var ctx = document.getElementById('chartSexe').getContext('2d');
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Masculin', 'Féminin'],
                                        datasets: [{
                                            label: 'Nombre de clients',
                                            data: [{{ $maleCustomersCount }}, {{ $femaleCustomersCount }}],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                            ],
                                            borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
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
