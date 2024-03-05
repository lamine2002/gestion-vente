@extends('admin.dashboard')

@section('title', $user->exists ? "Modifier un utilisateur" : "Créer un utilisateur")

@section('content')
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>

    <form action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
          method="post" class="space-y-6" enctype="multipart/form-data">

        @csrf
        @if($user->exists)
            @method('PATCH')
        @endif

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            @include('share.input', ['label' => 'Nom', 'name' => 'name', 'value' => old('name', $user->name)])
            @include('share.input', ['label' => 'Email', 'name' => 'email', 'value' => old('email', $user->email)])
            @include('share.input', ['label' => 'Mot de passe', 'name' => 'password', 'type' => 'password'])
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role" class="mt-1 block
                w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500  focus:border-indigo-500 sm:text-sm">
                    <option value="">Sélectionner le role</option>
                    @foreach($roles as $k => $v)
                        <option value="{{ $k }}" @if(old('role', $user->role) === $k) selected @endif>{{ $v }}</option>
                    @endforeach
                </select>
                @error('role')
                <div class="text-red-500 mt-1 text-sm">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        {{-- Ajouter une image       --}}
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">
                Image
            </label>
            <div class="mt-1 flex items-center">
                <span class="inline-block h-48 w-48 overflow-hidden rounded-full">
                    <img class="h-48 w-48 rounded-full" src="{{ $user->photo ? $user->imageUrl() : asset('images/default.png') }}" alt="">
                </span>
                <input type="file" name="photo" id="image" class="ml-5 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            @error('image')
            <div class="text-red-500 mt-1 text-sm">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                @if($user->exists)
                    Modifier
                @else
                    Créer
                @endif
            </button>
        </div>

    </form>

@endsection
