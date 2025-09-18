@extends('backend.layouts.app')

@section('title')
    {{ __($breadcrumbs['title']) }} | {{ config('app.name') }}
@endsection

@section('admin-content')
   <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Ventas') }}</h3>
                <div class="flex gap-2">
                   {{--  @if (auth()->user()->can('typecoin.create')) --}}
                        <a href="{{ route('admin.sales.create') }}" class="btn-primary">
                            <i class="bi bi-plus mr-2"></i>
                            {{ __('Agregar') }}
                        </a>
                   {{--  @endif --}}
                </div>
            </div>
            <div class="px-5 py-4 sm:px-6 sm:py-5 ">
                <table class="table-auto w-full border-collapse rounded-2xl">
                    <thead>
                        <tr>
                            {{-- <th class="px-4 py-2">{{ __('Código') }}</th> --}}
                            <th class="px-4 py-2">{{ __('Cliente') }}</th>
                            <th class="px-4 py-2">{{ __('Productos') }}</th>
                            <th class="px-4 py-2">{{ __('Monto total') }}</th>
                            <th class="px-4 py-2">{{ __('Moneda a pagar') }}</th>
                            <th class="px-4 py-2">{{ __('Caja registra') }}</th>
                            <th class="px-4 py-2">{{ __('Pagado en Moneda Extranjera') }}</th>
                             <th class="px-4 py-2">{{ __('Tasa de Cambio') }}</th>
                            <th class="px-4 py-2">{{ __('Monto Pagado') }}</th>
                            <th class="px-4 py-2">{{ __('Vuelto') }}</th>
                            <th class="px-4 py-2">{{ __('Cajero') }}</th>
                            <th class="px-4 py-2">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                   {{--  <td class="border px-4 py-2">{{ $sale->code }}</td> --}}
                                   <td class="border px-4 py-2" style="width: 150px;">{{ $sale->client->name }}</td>
                                    <td class="border px-4 py-2" style="width: 150px;">
                                        @if($sale->inventories && $sale->inventories->count())
                                            <ul class="flex flex-col gap-2 pl-0">
                                                @foreach($sale->inventories->take(3) as $inventory)
                                                    <li class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $inventory->name }}</span>
                                                        <span class="bg-black text-white font-medium py-1 px-3 rounded-md shadow-sm text-xs">
                                                            {{ $inventory->pivot->quantity }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                                @if($sale->inventories->count() > 3)
                                                    <li>
                                                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="text-blue-600 hover:underline">
                                                            <span class="text-gray-500">+{{ $sale->inventories->count() - 4 }} más</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        @else
                                            <span class="text-gray-400">Sin productos</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $sale->amount_total }}</td>
                                    <td class="border px-4 py-2">{{ $sale->typeCoin->name }}</td>
                                    <td class="border px-4 py-2">{{ $sale->box->name }}</td>
                                    <td class="border px-4 py-2">@if($sale->amount_foreign_currency){{ $sale->amount_foreign_currency }} {{ $sale->typeCoin->symbol }} @else {{ 'N/A' }}@endif</td>
                                    <td class="border px-4 py-2">@if($sale->exchange_rate){{ $sale->exchange_rate }} {{ $sale->typeCoin->symbol }} @else {{ 'N/A' }}@endif</td>
                                    <td class="border px-4 py-2">{{ $sale->amount }} {{ 'USD' }}</td>
                                    <td class="border px-4 py-2">{{ $sale->return_change }} {{ 'USD' }}</td>
                                    <td class="border px-4 py-2">{{ $sale->user->name }}</td>
                                   {{--  <td class="border px-4 py-2">{{ $sale->user->name }}</td> --}}
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2 text-center justify-center">
                                         {{--   @if (auth()->user()->can('sales.edit'))
                                                 <div x-data="{ editModalOpen: false }">
                                                    <!-- Botón para abrir el modal -->
                                                    <x-buttons.action-item
                                                        type="modal-trigger"
                                                        modal-target="editModalOpen"
                                                        icon="pencil"
                                                        :label="__('')"
                                                        class="text-blue-600 hover:underline"
                                                    />

                                                    <!-- Modal personalizado -->
                                                    <div 
                                                        x-show="editModalOpen" 
                                                        x-transition.opacity.duration.200ms 
                                                        x-cloak 
                                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                                    >
                                                        <div 
                                                            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6"
                                                            @click.away="editModalOpen = false"
                                                        >
                                                            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                                                                {{ __('Editar Venta') }}
                                                            </h2>
                                                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                                                {{ __('¿Estás seguro de que deseas editar esta venta?') }}
                                                            </p>

                                                            <div class="flex justify-end gap-2 mt-4">
                                                                <button 
                                                                    @click="editModalOpen = false" 
                                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                                                >
                                                                    {{ __('No, cancelar') }}
                                                                </button>

                                                                <a 
                                                                    href="{{ route('admin.sales.edit', $sale->id) }}" 
                                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800"
                                                                >
                                                                    {{ __('Sí, Editar') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif --}}

                                            @if (auth()->user()->can('sales.delete'))
                                                <div x-data="{ deleteModalOpen: false }">
                                                    <x-buttons.action-item
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash"
                                                        :label="__('')"
                                                        class="text-red-600 hover:underline"
                                                    />

                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $sale->id }}"
                                                        title="{{ __('Eliminar Venta') }}"
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash" 
                                                        :label="__('')" 
                                                        class="text-red-600 hover:underline"
                                                    />
                                                    
                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $sale->id }}"
                                                        title="{{ __('Eliminar Venta') }}"
                                                        content="{{ __('¿Estás seguro de que deseas eliminar esta venta?') }}"
                                                        formId="delete-form-{{ $sale->id }}"
                                                        formAction="{{ route('admin.sales.destroy', $sale->id) }}"
                                                        modalTrigger="deleteModalOpen"
                                                        cancelButtonText="{{ __('No, cancelar') }}"
                                                        confirmButtonText="{{ __('Sí, Confirmar') }}"
                                                    />
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {!! $sales->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection