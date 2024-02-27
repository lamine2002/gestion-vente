@extends('admin.dashboard')

@section('title', $order->exists ? "Modifier la commande $order->numOrder" : "Créer une commande")

@section('content')

    <form action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="post">
        @csrf
        @if($order->exists)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="customer_id" class="block font-medium text-sm text-gray-700">Client</label>
            <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @foreach($customers as $customerId => $customerName)
                    <option value="{{ $customerId }}" {{ old('customer_id', $order->customer_id) == $customerId ? 'selected' : '' }}>{{ $customerName }}</option>
                @endforeach
            </select>
            @error('customer_id')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="user_id" class="block font-medium text-sm text-gray-700">Utilisateur</label>
            <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @foreach($users as $userId => $userName)
                    <option value="{{ $userId }}" {{ old('user_id', $order->user_id) == $userId ? 'selected' : '' }}>{{ $userName }}</option>
                @endforeach
            </select>
            @error('user_id')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block font-medium text-sm text-gray-700">Statut</label>
            <input type="text" name="status" id="status" value="{{ old('status', $order->status) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('status')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="payment" class="block font-medium text-sm text-gray-700">Paiement</label>
            <input type="text" name="payment" id="payment" value="{{ old('payment', $order->payment) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('payment')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="numOrder" class="block font-medium text-sm text-gray-700">Numéro de commande</label>
            <input type="text" name="numOrder" id="numOrder" value="{{ old('numOrder', $order->numOrder) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('numOrder')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="orderDate" class="block font-medium text-sm text-gray-700">Date de commande</label>
            <input type="date" name="orderDate" id="orderDate" value="{{ old('orderDate', $order->orderDate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('orderDate')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="total" class="block font-medium text-sm text-gray-700">Total</label>
            <input type="text" name="total" id="total" value="{{ old('total', $order->total) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            @error('total')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="products-list">
            <div class="mb-4">
                <label for="product1" class="block font-medium text-sm text-gray-700">Produit</label>
                <select name="products[]" id="product1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach($products as $productId => $productName)
                        <option value="{{ $productId }}">{{ $productName }}</option>
                    @endforeach
                </select>
                @error('products')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="quantity1" class="block font-medium text-sm text-gray-700">Quantité</label>
                <input type="text" name="quantities[]" id="quantity1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('quantities')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <button type="button" onclick="addProductRow()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ajouter Produit</button>
        </div>

        <div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enregistrer</button>
        </div>
    </form>

    <script>
        let productIndex = 1;

        function addProductRow() {
            productIndex++;
            const productSelect = document.getElementById('product1').cloneNode(true);
            const quantityInput = document.getElementById('quantity1').cloneNode(true);
            productSelect.setAttribute('name', 'products[]');
            productSelect.setAttribute('id', 'product' + productIndex);
            quantityInput.setAttribute('name', 'quantities[]');
            quantityInput.setAttribute('id', 'quantity' + productIndex);

            const productsList = document.getElementById('products-list');
            const newDiv = document.createElement('div');
            newDiv.classList.add('mb-4');
            newDiv.innerHTML = `
                <label for="product${productIndex}" class="block font-medium text-sm text-gray-700">Produit</label>
                ${productSelect.outerHTML}
                <label for="quantity${productIndex}" class="block font-medium text-sm text-gray-700">Quantité</label>
                ${quantityInput.outerHTML}
            `;
            productsList.appendChild(newDiv);
        }
    </script>

@endsection
