@extends('admin.dashboard')

{{-- utilisateur en connexion--}}
@php
    $user= auth()->user();
//    dd($user->id);
@endphp



@section('title', $order->exists ? "Modifier la commande $order->numOrder" : "Créer une commande")

@section('content')
    <div class="flex justify-between mb-8">
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>

    <form action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}" method="post">
        @csrf
        @if($order->exists)
            @method('PATCH')
        @endif

        {{-- Numero, date, et mode de paiement de commande       --}}
        <div class="flex justify-between">
            <div class="px-3 py-2 w-1/3">
                <label for="numOrder" class="block font-medium text-sm text-gray-700">Numéro de Commande</label>
                <input type="text" name="numOrder" id="numOrder" value="{{ $order->exists ? $order->numOrder : 'CMD-'.date('Ymd').'-'.rand(1000, 9999) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                @error('numOrder')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-3 py-2 w-1/3">
                <label for="orderDate" class="block font-medium text-sm text-gray-700">Date de Commande</label>
                <input type="date" name="orderDate" id="orderDate" value="{{ old('orderDate', $order->orderDate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('orderDate')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-3 py-2 w-1/3">
                <label for="payment" class="block font-medium text-sm text-gray-700">Mode de Paiement</label>
                <select name="payment" id="payment" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un mode de paiement</option>
                    <option value="Espece" {{ old('payment', $order->payment) === 'Espece' ? 'selected' : '' }}>Espece</option>
                    <option value="Chèque" {{ old('payment', $order->payment) === 'Chèque' ? 'selected' : '' }}>Chèque</option>
                    <option value="Carte Bancaire" {{ old('payment', $order->payment) === 'Carte Bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                </select>
                @error('payment')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Statut et Utilisateur       --}}
        <div class="flex justify-between">
            @can('update', $order)
            <div class="px-3 py-2 w-1/2">
                <label for="status" class="block font-medium text-sm text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Sélectionner un statut</option>
                    <option value="En attente" {{ old('status', $order->status) === 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="En cours de traitement" {{ old('status', $order->status) === 'En cours de traitement' ? 'selected' : '' }}>En cours de traitement</option>
                    <option value="Terminée" {{ old('status', $order->status) === 'Terminée' ? 'selected' : '' }}>Terminée</option>
                    <option value="Annulée" {{ old('status', $order->status) === 'Annulée' ? 'selected' : '' }}>Annulée</option>
                </select>
                @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endcan

            @cannot('update', $order)
            <div class="px-3 py-2 w-1/2">
                <label for="status" class="block font-medium text-sm text-gray-700">Statut</label>
                <input type="text" name="status" id="status" value="{{ $order->exists ? $order->status : "En attente" }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                @error('status')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endcannot

            <div class="px-3 py-2 w-1/2">
                <label for="user_id" class="block font-medium text-sm text-gray-700">Commande faite par </label>
                <input type="text" name="user_name" id="user_name" value="{{ $order->exists ? \App\Models\User::find($order->user_id)->name: $user->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                <input type="text" name="user_id" id="user_id" value="{{ $order->exists ? \App\Models\User::find($order->user_id)->id: $user->id }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 hidden" readonly>
                @error('user_id')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        {{-- Choix du client       --}}
        {{--        @dd($customers[0]->firstname)--}}
        <div class="flex justify-between mb-4">
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

        {{-- Ajout, Modification dans le tableau de produits commandes       --}}
        <div class="flex justify-between mb-4">
            <div class="px-2 py-2 w-1/5">
                <label for="product1" class="block font
                -medium text-sm text-gray-700">Produit</label>
                <select name="product1" id="product1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Choisir un produit</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product1')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- Quantite disponible           --}}
            <div class="px-2 py-2 w-1/5">
                <label for="quantityAvailable" class="block font
                -medium text-sm text-gray-700">Quantité disponible</label>
                <input type="number" name="quantityAvailable" id="quantityAvailable" value="{{ old('quantityAvailable') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                @error('quantityAvailable')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-2 py-2 w-1/5">
                <label for="quantityToOrder" class="block font
                -medium text-sm text-gray-700">Quantité commandé</label>
                <input type="number" min="1" max="" name="quantityToOrder" id="quantityToOrder" value="{{ old('quantityToOrder') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('quantityToOrder')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-2 py-2 w-1/5">
                <label for="price" class="block font
                -medium text-sm text-gray-700">Prix Produit</label>
                <input type="text"  name="price" id="price" value="{{ old('price') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                @error('price')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-2 py-2 w-1/5">
                <label for="totalLine" class="block font
                -medium text-sm text-gray-700">Total</label>
                <input type="text"  name="totalLine" id="totalLine" value="{{ old('totalLine') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                @error('totalLine')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4 flex justify-center">
            <div>
                <button type="button" id="addProduct" onclick="addProductRow()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4">Ajouter Produit</button>
                <button type="button" id="editProduct" onclick="updateProductOrder()" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4 hidden">Modifier Produit</button>
            </div>
                <button type="button" onclick="clearAddproductField()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Annuler</button>
        </div>

        {{-- tableau de produits a achetes et leur quantitte       --}}
        <div class="overflow-x-auto bg-white rounded shadow  mb-8">
            <table class="w-full whitespace-no-wrap">
                <thead>
                <tr
                    class="text-2xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                    <th class="px-4 py-3">Produit</th>
                    <th class="px-4 py-3">Quantite Commandé</th>
                    <th class="px-4 py-3">Prix Produit</th>
                    <th class="px-4 py-3">Total Ligne</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                    @if($order->exists)
                        @foreach($order->products as $product)
                                <tr class="text-sm text-gray-500" id="product[]">
                                    <td class="px-4 py-3">
                                        <input type="text" name="products[]" value="{{ $product->id }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 hidden" >
                                        <input type="text" name="productsNames[]" value="{{ $product->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="quantities[]" value="{{ $product->pivot->quantity }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="prices[]" value="{{ $product->price }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="lineTotal[]" value="" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                    </td>
                                    <td class="px-4 py-3 ">
                                        <a href="#" class="text-red-500 hover:text-red-700 mr-2" onclick="deleteProductRow(this)">Supprimer</a>
                                        <a href="#" class="text-blue-500 hover:text-blue-700" onclick="editProductRow(this)">Modifier</a>
                                    </td>
                                </tr>
                        @endforeach
                    @endif

                            {{--  Gerer le montant total                            --}}
                    <tr class="text-sm text-gray-500">
                        <td class="px-4 py-3" colspan="3"></td>
                        <td class="px-4 py-3">Montant Total</td>
                        <td class="px-4 py-3">
                            <input type="text" name="total" value="{{ $order->total }}" class="mt-1 block w-full border-none rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>





        <div class="mb-4 flex justify-center">
            @if($order->exists)
                <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="validateOrder()">Modifier la Commande</button>
            @else
                <button type="submit" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="validateOrder()">Valider la Commande</button>
            @endif

        </div>
    </form>

{{--    @dd($customers->toJson())--}}

    <script>
        const customers = @json($customers);
        const products = @json($products);
        // console.log(customers);
        console.log(products);
        const customerSelect = document.getElementById('customer_id');
        const customerAddress = document.getElementById('customerAddress');
        const customerTel = document.getElementById('customerTel');
        const productsList = document.getElementById('products-list');

        let currentCustomerSelected = customerSelect.value;
        // console.log(currentCustomerSelected);
        if (currentCustomerSelected) {
            const selectedCustomer = customers.find(customer => customer.id === parseInt(currentCustomerSelected));
            customerAddress.value = selectedCustomer.address;
            customerTel.value = selectedCustomer.phone;
        }

        customerSelect.addEventListener('change', function() {
            const selectedCustomer = customers.find(customer => customer.id === parseInt(this.value));
            customerAddress.value = selectedCustomer.address;
            customerTel.value = selectedCustomer.phone;
        });

        // Partie pour ajouter un produit dans la commande
        const productSelect = document.getElementById('product1');
        const quantityInput = document.getElementById('quantityAvailable');
        const priceInput = document.getElementById('price');
        const totalInput = document.getElementById('totalLine');
        const quantityToOrder = document.getElementById('quantityToOrder');

        if (productSelect.value) {
            const selectedProduct = products.find(product => product.id === parseInt(productSelect.value));
            quantityInput.value = selectedProduct.stock;
            priceInput.value = selectedProduct.price;


        }

        quantityToOrder.addEventListener('change', function() {
            if (parseInt(this.value) > parseInt(quantityInput.value)) {
                this.value = quantityInput.value;
            }else if (parseInt(this.value) <= 0) {
                this.value = 1;
            } else {
                totalInput.value = parseInt(this.value) * parseInt(priceInput.value);
            }

        });


        productSelect.addEventListener('change', function() {
            const selectedProduct = products.find(product => product.id === parseInt(this.value));
            quantityInput.value = selectedProduct.stock;
            priceInput.value = selectedProduct.price;
        });

        function clearAddproductField() {
            productSelect.value = '';
            quantityInput.value = '';
            priceInput.value = '';
            totalInput.value = '';
            quantityToOrder.value = '';
            const editProduct = document.getElementById('editProduct');
            const addProduct = document.getElementById('addProduct');
            if (!(editProduct.classList.contains('hidden'))) {
                addProduct.classList.remove('hidden');
                editProduct.classList.add('hidden');
            }
        }

        function validateOrder() {
            const products = document.querySelectorAll('input[name="products[]"]');
            const quantities = document.querySelectorAll('input[name="quantities[]"]');
            const prices = document.querySelectorAll('input[name="prices[]"]');
            const lineTotals = document.querySelectorAll('input[name="lineTotal[]"]');
            const total = document.querySelector('input[name="total"]');
            if (products.length === 0) {
                alert('Veuillez ajouter au moins un produit dans la commande');
                return;
            }
            if (total.value === '0') {
                alert('Le montant total de la commande ne peut pas être égal à 0');
                return;
            }
        }


        function addProductRow() {
            const productSelect = document.getElementById('product1');
            const quantityInput = document.getElementById('quantityAvailable');
            const priceInput = document.getElementById('price');
            const totalInput = document.getElementById('totalLine');
            const quantityToOrder = document.getElementById('quantityToOrder');
            const tbody = document.querySelector('tbody');
            const product = productSelect.options[productSelect.selectedIndex].text;
            const quantity = quantityToOrder.value;
            const price = priceInput.value;
            const total = totalInput.value;
            if (!product || !quantity || !price || !total) {
                alert('Veuillez remplir tous les champs');
                return;
            }
            const row = document.createElement('tr');
            row.classList.add('text-sm', 'text-gray-500');
            row.id = 'product[]';
            row.innerHTML = `
                <td class="px-4 py-3">
                    <input type="text" name="products[]" value="${productSelect.value}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 hidden" >
                    <input type="text" name="productsNames[]" value="${product}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="quantities[]" value="${quantity}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="prices[]" value="${price}" class="mt-1 block
                    w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                </td>
                <td class="px-4 py-3">
                    <input type="text" name="lineTotal[]" value="${total}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                </td>
                <td class="px-4 py-3 ">
                    <a href="#" class="text-red-500 hover:text-red-700 mr-2" onclick="deleteProductRow(this)">Supprimer</a>
                    <a href="#" class="text-blue-500 hover:text-blue-700" onclick="editProductRow(this)">Modifier</a>
                </td>
            `;
            // avant d'ajouter la ligne dans le tableau, on verifie si le produit n'est pas deja dans le tableau
            const products = document.querySelectorAll('input[name="products[]"]');
            let productExist = false;
            products.forEach(product => {
                if (product.value === productSelect.value) {
                    productExist = true;
                }
            });
            if (productExist) {
                alert('Ce produit est déjà dans la commande');
                return;
            }

            // ajouter la ligne dans le tableau avant la derniere ligne
            tbody.insertBefore(row, tbody.lastElementChild);
            calculateTotal();
            clearAddproductField();

        }

        // supprimer une ligne de produit
        function deleteProductRow(button) {
            if (productSelect.value) {
                alert('Veuillez annuler la modification ou l\'ajout  en cours avant de modifier une autre ligne');
                return;
            }
            const row = button.closest('tr');
            row.remove();
            calculateTotal();
        }

        // modifier une ligne de produit
        function editProductRow(button) {
            if (productSelect.value) {
                alert('Veuillez annuler la modification ou l\'ajout  en cours avant de modifier une autre ligne');
                return;
            }

            // Get the row element
            const row = button.closest('tr');

            // Get the product details from the row
            const productId = row.querySelector('input[name="products[]"]').value;
            const quantity = row.querySelector('input[name="quantities[]"]').value;
            const price = row.querySelector('input[name="prices[]"]').value;

            // Set the product details in the form
            document.getElementById('product1').value = productId;
            document.getElementById('quantityToOrder').value = quantity;
            document.getElementById('price').value = price;

            // Store the row being edited
            row.classList.add('editing');
            document.getElementById('addProduct').classList.add('hidden');
            document.getElementById('editProduct').classList.remove('hidden');

        }

        function updateProductOrder() {
            // Get the row being edited
            const row = document.querySelector('.editing');
            console.log(row);

            // Get the product details from the form
            const product = document.getElementById('product1').value;
            const quantity = document.getElementById('quantityToOrder').value;
            const price = document.getElementById('price').value;
            const total = document.getElementById('totalLine').value;
            // verifie si les champs sont remplis
            if (!product || !quantity || !price || !total) {
                alert('Veuillez remplir tous les champs');
                return;
            }
            // Update the row with the new product details
            row.querySelector('input[name="products[]"]').value = product;
            row.querySelector('input[name="productsNames[]"]').value = document.getElementById('product1').options[document.getElementById('product1').selectedIndex].text;
            row.querySelector('input[name="quantities[]"]').value = quantity;
            row.querySelector('input[name="prices[]"]').value = price;
            row.querySelector('input[name="lineTotal[]"]').value = total;
            calculateTotal();
            clearAddproductField();
            // Remove the editing class from the row
            row.classList.remove('editing');
            document.getElementById('addProduct').classList.remove('hidden');
            document.getElementById('editProduct').classList.add('hidden');

        }



        // gerer dynamiquement le montant total
        function calculateTotal() {
            const products = document.querySelectorAll('input[name="products[]"]');
            const quantities = document.querySelectorAll('input[name="quantities[]"]');
            const prices = document.querySelectorAll('input[name="prices[]"]');
            const lineTotals = document.querySelectorAll('input[name="lineTotal[]"]');
            const total = document.querySelector('input[name="total"]');
            console.log(products.length, quantities.length, prices.length, lineTotals.length);
            let total2 = 0;
            for (let i = 0; i < products.length; i++) {
                lineTotals[i].value = parseInt(quantities[i].value) * parseInt(prices[i].value);
                total2 += parseInt(quantities[i].value) * parseInt(prices[i].value);
            }
            total.value = total2;
        }

        calculateTotal();

        // mettre le select de user_id en disabled si on est pas admin
        {{--const userSelect = document.getElementById('user_id');--}}
        {{--userSelect.disabled = --}}{{--{{ auth()->user()->can('update', $order) ? 'false' : 'true' }}--}}{{--;--}}

    </script>



@endsection
