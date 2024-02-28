@extends('admin.dashboard')

@section('title', 'Commandes')

@section('content')
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.orders.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ajouter une Commande</a>
    </div>


@endsection
