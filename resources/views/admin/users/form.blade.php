@extends('admin.dashboard')

@section('title', $user->exists ? "Modifier un utilisateur" : "Créer un utilisateur")

@section('content')

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

        <div class="w-1/2">
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-50 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="dropzone-file" name="photo" type="file" class="hidden" />
                </label>
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
