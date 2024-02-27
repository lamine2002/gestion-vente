@extends('admin.dashboard')

@section('title', $order->exists ? "Modifier la commande $order->numOrder" : "Créer une commande")

@section('content')

    <form action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="post">
        @csrf
        @if($order->exists)
            @method('PUT')
        @endif




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
