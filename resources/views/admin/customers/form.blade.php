@extends('admin.dashboard')

@section('title', $customer->exists ? "Modifier un client" : "Créer un client")

@section('content')

    <form action="{{ $customer->exists ? route('admin.customers.update', $customer) : route('admin.customers.store') }}"
          method="post" class="space-y-6">

        @csrf
        @if($customer->exists)
            @method('PATCH')
        @endif

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            @include('share.input', ['label' => 'Prénom', 'name' => 'firstname', 'value' => old('firstname', $customer->firstname)])
            @include('share.input', ['label' => 'Nom', 'name' => 'lastname', 'value' => old('lastname', $customer->lastname)])
            @include('share.input', ['label' => 'Adresse', 'name' => 'address', 'value' => old('address', $customer->address)])
            @include('share.input', ['label' => 'Téléphone', 'name' => 'phone', 'value' => old('phone', $customer->phone)])
            <div>
                <label for="sex" class="block text-sm font-medium text-gray-700">Sexe</label>
                <select id="sex" name="sex" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Sélectionner le sexe</option>
                    <option value="M" @if(old('sex', $customer->sex) === 'M') selected @endif>Masculin</option>
                    <option value="F" @if(old('sex', $customer->sex) === 'F') selected @endif>Féminin</option>
                </select>
                @error('sex')
                <div class="text-red-500 mt-1 text-sm">
                    {{ $message }}
                </div>
                @enderror
            </div>


        </div>

        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                @if($customer->exists)
                    Modifier
                @else
                    Créer
                @endif
            </button>
        </div>

    </form>

@endsection
