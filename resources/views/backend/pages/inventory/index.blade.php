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
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Detalles del inventario') }}</h3>
                <div class="flex gap-2">
                    @if (auth()->user()->can('box.edit'))
                        <a href="{{ route('admin.inventories.edit', $box->id) }}" class="btn-primary">
                            <i class="bi bi-pencil mr-2"></i>
                            {{ __('Editar') }}
                        </a>
                    @endif
                    <a href="{{ route('admin.inventories.create') }}" class="btn-default">
                        <i class="bi bi-plus mr-2"></i>
                        {{ __('Registrar compra') }}
                    </a>
                </div>
            </div>

            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('Nombre del producto') }}</th>
                                <th class="px-4 py-2">{{ __('Proveedor') }}</th>
                                <th class="px-4 py-2">{{ __('Codigo') }}</th>
                                <th class="px-4 py-2">{{ __('Stock') }}</th>
                                <th class="px-4 py-2">{{ __('Tipo de moneda') }}</th>
                                <th class="px-4 py-2">{{ __('Monto') }}</th>
                                <th class="px-4 py-2">{{ __('Fecha de creación') }}</th>
                                <th class="px-4 py-2">{{ __('Fecha de actualización') }}</th>
                                <th class="px-4 py-2">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->name }}</td>
                                    <td class="border px-4 py-2">{{ $item->supplier->name ?? '' }}</td>
                                    <td class="border px-4 py-2">{{ $item->code }}</td>
                                    <td class="border px-4 py-2">{{ $item->stock }}</td>
                                    <td class="border px-4 py-2">{{ $item->typeCoin->name }}</td>
                                    <td class="border px-4 py-2">{{ $item->amount }} {{  $item->typeCoin->symbol }}</td>
                                    <td class="border px-4 py-2">{{ $item->created_at ?? '' }}</td>
                                    <td class="border px-4 py-2">{{ $item->updated_at ?? '' }}</td>
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2">
                                           {{--  @if (auth()->user()->can('box.edit')) --}}
                                                <a href="{{ route('admin.inventories.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            {{-- @endif --}}
                                            {{-- @if (auth()->user()->can('box.delete')) --}}
                                                <form action="{{ route('admin.inventories.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('¿Estás seguro de eliminar este producto?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            {{-- @endif --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Box Content -->
                <div class="prose dark:prose-invert">
                    {!! $items->links() !!}
                </div>
                
            </div>
            <!-- Box Content -->
            
    </div>
</div>

@endsection
