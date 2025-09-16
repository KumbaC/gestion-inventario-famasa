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
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Proveedores') }}</h3>
                <div class="flex gap-2">
                   {{--  @if (auth()->user()->can('typecoin.create')) --}}
                        <a href="{{ route('admin.suppliers.create') }}" class="btn-primary">
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
                            <th class="px-4 py-2 text-center">{{ __('Nombre') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('RIF') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('Telefono') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('Estatus') }}</th>
                            <th class="px-4 py-2 text-center">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $supplier->name }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $supplier->rif }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $supplier->phone }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        @if ($supplier->is_active)
                                            <span class="text-green-600">{{ __('Activo') }}</span>
                                        @else
                                            <span class="text-red-600">{{ __('Inactivo') }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2 justify-center">
                                            
                                            {{-- @if (auth()->user()->can('typecoin.edit')) --}}
                                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="text-blue-600 hover:underline">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            {{-- @endif --}}
                                           {{--  @if (auth()->user()->can('typecoin.delete')) --}}
                                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('{{ __('¿Deseas borrar este proveedor?') }}');">
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
            </div>
        </div>
    </div>
</div>
@endsection