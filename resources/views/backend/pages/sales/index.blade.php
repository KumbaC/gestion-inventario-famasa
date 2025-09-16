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
                            <th class="px-4 py-2">{{ __('Productos') }}</th>
                            <th class="px-4 py-2">{{ __('Monto total') }}</th>
                            <th class="px-4 py-2">{{ __('Moneda a pagar') }}</th>
                            <th class="px-4 py-2">{{ __('Caja registra') }}</th>
                            <th class="px-4 py-2">{{ __('Pagado en Moneda Extranjera') }}</th>
                             <th class="px-4 py-2">{{ __('Tasa de Cambio') }}</th>
                            <th class="px-4 py-2">{{ __('Monto Pagado') }}</th>
                            <th class="px-4 py-2">{{ __('Usuario') }}</th>
                            <th class="px-4 py-2">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                   {{--  <td class="border px-4 py-2">{{ $sale->code }}</td> --}}
                                    <td class="border px-4 py-2">
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
                                    <td class="border px-4 py-2">{{ $sale->user->name }}</td>
                                   {{--  <td class="border px-4 py-2">{{ $sale->user->name }}</td> --}}
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2 justify-center">
                                            
                                                <a href="{{ route('admin.sales.show', $sale->id) }}" class="text-blue-600 hover:underline">
                                                            <span class="text-gray-500"><i class="bi bi-eye"></i></span>
                                                </a>
                                            {{-- @if (auth()->user()->can('typecoin.edit')) --}}
                                               {{--  <a href="{{ route('admin.sales.edit', $sale->id) }}" class="text-blue-600 hover:underline">
                                                    <i class="bi bi-pencil"></i>
                                                </a> --}}
                                            {{-- @endif --}}
                                           {{--  @if (auth()->user()->can('typecoin.delete')) --}}
                                                <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this item?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                           {{--  @endif --}}
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