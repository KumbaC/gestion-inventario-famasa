@extends('backend.layouts.app')

@section('title')
    {{ config('app.name') }}
@endsection

@section('admin-content')

<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
   {{--  <x-breadcrumbs :breadcrumbs="$breadcrumbs" /> --}}

    {!! ld_apply_filters('users_after_breadcrumbs', '') !!}
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class ="card">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Detalles de la Venta') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('ID de la venta: ') . $sale->id }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Fecha de la venta: ') . $sale->created_at->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total: ') . $sale->amount }} {{ $sale->typeCoin->symbol }}</p>
                            
                            </div>
                            <div class="card">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Detalles del Cliente') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Nombre: ') . $sale->client->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Identificación: ') . $sale->client->identification }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Teléfono: ') . $sale->client->phone }}</p>
                               {{--  <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Dirección: ') . $sale->client->address }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Productos Vendidos') }}</h3>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Producto') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Cantidad') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Precio Unitario') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($sale->inventories as $inventory)
                                <tr>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $inventory->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $inventory->pivot->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $inventory->amount }} {{ __('USD') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ __('Total') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->amount_total }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $sale-> }}</td> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection