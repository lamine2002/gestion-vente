@extends('admin.dashboard')

@section('title', $order->exists ? "Modifier la commande $order->numOrder" : "Créer une commande")

@section('content')

    <form action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="post">
        @csrf
        @if($order->exists)
            @method('PUT')
        @endif
{{--        @dd($customers[0]->firstname)--}}
        <div class="flex justify-between">
            <div class="px-3 py-2 w-1/3">
                <label for="customer_id" class="block font-medium text-sm text-gray-700">Client</label>
                <select name="customer_id" id="customer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un client</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->firstname.' '.$customer->lastname }}</option>
                    @endforeach
                </select>
                @error('customer_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-3 py-2 w-1/3">
                <label for="numOrder" class="block font-medium text-sm text-gray-700">Adresse Client</label>
                <input type="text" name="customerAddress" id="customerAddress" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
            </div>

            <div class="px-3 py-2 w-1/3">
                <label for="numOrder" class="block font-medium text-sm text-gray-700">Numéro de Telephone</label>
                <input type="text" name="customerTel" id="customerTel" value="" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
            </div>

        </div>


        <div id="products-list">
            <div class="mb-4">
                <label for="product1" class="block font
                -medium text-sm text-gray-700">Produit</label>

                <select name="products[]" id="product1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un produit</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('products.0')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="quantity1" class="block font
                -medium text-sm text-gray-700">Quantité</label>
                <input type="number" name="quantities[]" id="quantity1" value="{{ old('quantities.0') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('quantities.0')
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

{{--    @dd($customers->toJson())--}}

    <script>
        const customers = @json($customers);
        const products = @json($products);
        const customerSelect = document.getElementById('customer_id');
        const customerAddress = document.getElementById('customerAddress');
        const customerTel = document.getElementById('customerTel');
        const productsList = document.getElementById('products-list');

        customerSelect.addEventListener('change', function() {
            const selectedCustomer = customers.find(customer => customer.id === parseInt(this.value));
            customerAddress.value = selectedCustomer.address;
            customerTel.value = selectedCustomer.phone;
        });
    </script>


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
