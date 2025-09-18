@extends('backend.layouts.app')

@section('title')
    {{ __($breadcrumbs['title']) }} | {{ config('app.name') }}
@endsection

@section('admin-content')

<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    {!! ld_apply_filters('users_after_breadcrumbs', '') !!}
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.sales.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="code" id="code" value="{{ $code }}">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div>
                                <label for="box_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cajas') }}</label>
                               <select name="box_id" id="box_id" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="">{{ __('Seleccione una caja') }}</option>
                                    @foreach ($box as $boxes)
                                        <option 
                                            value="{{ $boxes->id }}" 
                                            data-type-coin-id="{{ $boxes->type_coin_id }}"
                                            {{ old('box_id') == $boxes->id ? 'selected' : '' }}>
                                            {{ $boxes->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Clientes') }}</label>
                               <select name="client_id" id="client_id" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">{{ __('Seleccione un cliente') }}</option>
                                    @foreach ($client as $clients)
                                        <option value="{{ $clients->id }}" {{ old('client_id') == $clients->id ? 'selected' : '' }}>
                                            {{ $clients->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Productos y cantidades') }}</label>
                            <input type="text" id="product-search" placeholder="Buscar producto..." class="mb-2 block w-full rounded border-gray-300 dark:bg-gray-800 dark:text-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <div id="products-list" class="space-y-2 max-h-64 overflow-y-auto border rounded p-2 bg-white dark:bg-gray-900">
                                @foreach($products as $product)
                                    <div class="flex items-center gap-2 product-item" data-name="{{ strtolower($product->name) }}">
                                        <input type="number" min="0" name="products[{{ $product->id }}]" value="0" class="w-20 rounded border-gray-300 dark:bg-gray-800 dark:text-gray-300" />
                                        <span>{{ $product->name }} ({{ number_format($product->amount, 2) }})</span>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-gray-500">{{ __('Coloca la cantidad para cada producto (0 para ninguno).') }}</small>
                        </div>

                            <div class="col-span-2">
                                <label for="amount_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Monto total de productos') }}</label>
                                <input type="text" name="amount_total" id="amount_total" readonly class="mt-1 block w-full rounded-md border-gray-500 bg-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>
                                
                            <div class="col-span-2">
                                <label for="type_coin_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tipo de moneda') }}</label>
                                
                               <select name="type_coin_id" id="type_coin_id" required class="mt-1 block w-full rounded-md border-gray-500 bg-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" style="pointer-events: none;">
                                    <option value="">{{ __('Selecciona un tipo de moneda') }}</option>
                                    @foreach ($type_coins as $type_coin)
                                        <option value="{{ $type_coin->id }}" {{ old('type_coin_id') == $type_coin->id ? 'selected' : '' }}>
                                            {{ $type_coin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                
                            <div class="col-span-2">
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Total a pagar') }}</label>
                                <input type="text" name="amount" id="monto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>
                            
                            
                            
                            <div>
                                <label for="exchange_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tipo de cambio') }}</label>
                                <input type="text" name="exchange_rate" id="exchange_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>

                            <div>
                                <label for="amount_foreign_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Monto de tipo de cambio') }}</label>
                                <input type="text" name="amount_foreign_currency" id="amount_foreign_currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>
                            <input type="hidden" name="return_change" id="return_change" value="0">
                            <span id="amount_message" class=""></span>
                            
                            {{-- <div>
                                <label for="amount_foreign_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Monto de moneda extranjera') }}</label>
                                <input type="text" name="amount_foreign_currency" id="amount_foreign_currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div> --}}

                            
                            

                             
                        <div class="mt-6">
                            <button type="submit" class="btn-primary">{{ __('Guardar') }}</button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
        <script>
       document.addEventListener('DOMContentLoaded', function() {
        const productInputs = document.querySelectorAll('input[name^="products["]');
        const amountTotal = document.getElementById('amount_total');
        const productsData = @json($products);

         function updateTotal() {
            let total = 0;
            productInputs.forEach(input => {
                const id = input.name.match(/\d+/)[0];
                const product = productsData.find(p => p.id == id);
                const qty = parseInt(input.value) || 0;
                if (product && qty > 0) {
                    total += qty * parseFloat(product.amount);
                }
            });
            amountTotal.value = total.toFixed(2) + ' USD';
        }

        function validateAmount() {
        let totalAmount = 0;
        productInputs.forEach(input => {
            const id = input.name.match(/\d+/)[0];
            const product = productsData.find(p => p.id == id);
            const qty = parseInt(input.value) || 0;
            if (product && qty > 0) {
                totalAmount += qty * parseFloat(product.amount);
            }
        });

            let amountExchangeRate = document.getElementById('exchange_rate').value;
            let amountForeignCurrency = document.getElementById('amount_foreign_currency').value;
            let changeAmount = document.getElementById('monto');
            let typeCoinId = document.getElementById('type_coin_id').value;

            if (typeCoinId == 1) {
                if (parseFloat(changeAmount.value) === totalAmount) {
                    document.getElementById('amount_message').innerHTML = '<span class="text-green-500">Monto correcto</span>';
                } else if (parseFloat(changeAmount.value) < totalAmount) {
                    document.getElementById('amount_message').innerHTML = '<span class="text-red-500">Monto insuficiente</span>';
                } else if (parseFloat(changeAmount.value) > totalAmount) {
                    document.getElementById('amount_message').innerHTML = '<span class="text-warning-500">Vuelto restante: ' + (parseFloat(changeAmount.value) - totalAmount).toFixed(2) + ' USD </span>';
                    document.getElementById('return_change').value = (parseFloat(changeAmount.value) - totalAmount).toFixed(2);
                } else {
                    document.getElementById('amount_message').innerHTML = '<span class="text-red-500">Monto incorrecto</span>';
                }
            } else {
                let change = (amountForeignCurrency / amountExchangeRate).toFixed(2);
                if (change == 'NaN') {
                    document.getElementById('monto').value = '';
                } else {
                    document.getElementById('monto').value = change;
                    if (parseFloat(changeAmount.value) === totalAmount) {
                        document.getElementById('amount_message').innerHTML = '<span class="text-green-500">Monto correcto</span>';
                    } else if (parseFloat(changeAmount.value) < totalAmount) {
                        document.getElementById('amount_message').innerHTML = '<span class="text-red-500">Monto insuficiente, falta: ' + (totalAmount - parseFloat(changeAmount.value)).toFixed(2) + ' USD</span>';
                    } else if (parseFloat(changeAmount.value) > totalAmount) {
                        document.getElementById('amount_message').innerHTML = '<span class="text-warning-500">Vuelto restante: ' + (parseFloat(changeAmount.value) - totalAmount).toFixed(2) + ' USD </span>';
                        document.getElementById('return_change').value = (parseFloat(changeAmount.value) - totalAmount).toFixed(2);
                    } else {
                        document.getElementById('amount_message').innerHTML = '<span class="text-red-500">Monto incorrecto</span>';
                    }
                }
            }
        }

        // Ejecutar al escribir en el input de monto
        document.getElementById('monto').addEventListener('input', validateAmount);
        // Ejecutar al cambiar productos (cantidad)
        productInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateTotal();
                validateAmount();
            });
        });
        // Ejecutar al cambiar tipo de cambio
        document.getElementById('exchange_rate').addEventListener('input', validateAmount);
        // Ejecutar al cambiar monto moneda extranjera
        document.getElementById('amount_foreign_currency').addEventListener('input', validateAmount);
        // Ejecutar al cambiar tipo de moneda
        document.getElementById('type_coin_id').addEventListener('change', validateAmount);
        // Ejecutar al cargar la página
        updateTotal();
        validateAmount();

        const searchInput = document.getElementById('product-search');
        const productItems = document.querySelectorAll('.product-item');

        searchInput.addEventListener('input', function() {
            const search = this.value.toLowerCase();
            productItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(search)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        let changeAmount = document.getElementById('monto');
        

        // Ejecutar al escribir en el input
        changeAmount.addEventListener('input', validateAmount);
        // Ejecutar al cambiar productos
        //selectedValue.addEventListener('change', validateAmount);
        // Ejecutar al cambiar tipo de cambio
        document.getElementById('exchange_rate').addEventListener('input', validateAmount);
        // Ejecutar al cambiar monto moneda extranjera
        document.getElementById('amount_foreign_currency').addEventListener('input', validateAmount);
        // Ejecutar al cambiar tipo de moneda
        document.getElementById('type_coin_id').addEventListener('change', validateAmount);
        // Ejecutar al cargar la página
        validateAmount();


        const boxSelect = document.getElementById('box_id');
        const typeCoinSelect = document.getElementById('type_coin_id');
        const inputexchangeRate = document.getElementById('exchange_rate');
        const inputAmount = document.getElementById('monto');
        const inputAmountForeignCurrency = document.getElementById('amount_foreign_currency');

        function updateTypeCoin() {
            const selectedOption = boxSelect.options[boxSelect.selectedIndex];
            const typeCoinId = selectedOption.getAttribute('data-type-coin-id');
            if(typeCoinId == 1){
                inputAmountForeignCurrency.readOnly = true;
                inputAmountForeignCurrency.className = "mt-1 block w-full rounded-md border-gray-500 bg-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
                inputAmount.readOnly = false;
                inputAmount.className = "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
                inputexchangeRate.readOnly = true;
                inputexchangeRate.className = "mt-1 block w-full rounded-md border-gray-500 bg-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
            }
            else{
                inputAmountForeignCurrency.readOnly = false;
                inputAmountForeignCurrency.className = "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
                inputAmount.readOnly = true;
                inputAmount.className = "mt-1 block w-full rounded-md border-gray-500 bg-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
                inputexchangeRate.readOnly = false;
                inputexchangeRate.className = "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300";
            }
            
            if (typeCoinId) {
                // Busca la opción cuyo valor coincida exactamente con typeCoinId
                for (let i = 0; i < typeCoinSelect.options.length; i++) {
                    if (typeCoinSelect.options[i].value == typeCoinId) {
                        typeCoinSelect.selectedIndex = i;
                        break;
                    }
                }
            } else {
                typeCoinSelect.selectedIndex = 0; // Selecciona la opción por defecto
            }
        }

        boxSelect.addEventListener('change', updateTypeCoin);

        // Selecciona automáticamente al cargar la página si ya hay una caja seleccionada
        updateTypeCoin();








    });
    </script>
   
@endsection
