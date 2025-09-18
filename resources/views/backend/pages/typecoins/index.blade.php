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
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Monedas') }}</h3>
                <div class="flex gap-2">
                   {{--  @if (auth()->user()->can('typecoin.create')) --}}
                        <a href="{{ route('admin.type-coins.create') }}" class="btn-primary">
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
                            <th class="px-4 py-2">{{ __('Nombre') }}</th>
                            <th class="px-4 py-2">{{ __('Abreviatura (Símbolo)') }}</th>
                            <th class="px-4 py-2">{{ __('Estado') }}</th>
                            <th class="px-4 py-2">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($typeCoins as $typeCoin)
                                <tr>
                                    <td class="border px-4 py-2">{{ $typeCoin->name }}</td>
                                    <td class="border px-4 py-2">{{ $typeCoin->symbol }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($typeCoin->is_active)
                                            <span class="text-green-600">{{ __('Activo') }}</span>
                                        @else
                                            <span class="text-red-600">{{ __('Inactivo') }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2 text-center justify-center">
                                           @if (auth()->user()->can('type-coins.edit'))
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
                                                                {{ __('Editar Tipo de Moneda') }}
                                                            </h2>
                                                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                                                {{ __('¿Estás seguro de que deseas editar este tipo de moneda?') }}
                                                            </p>

                                                            <div class="flex justify-end gap-2 mt-4">
                                                                <button 
                                                                    @click="editModalOpen = false" 
                                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                                                >
                                                                    {{ __('No, cancelar') }}
                                                                </button>

                                                                <a 
                                                                    href="{{ route('admin.type-coins.edit', $typeCoin->id) }}" 
                                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800"
                                                                >
                                                                    {{ __('Sí, Editar') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('type-coins.delete'))
                                                <div x-data="{ deleteModalOpen: false }">
                                                    <x-buttons.action-item
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash"
                                                        :label="__('')"
                                                        class="text-red-600 hover:underline"
                                                    />

                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $typeCoin->id }}"
                                                        title="{{ __('Eliminar Tipo de Moneda') }}"
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash" 
                                                        :label="__('')" 
                                                        class="text-red-600 hover:underline"
                                                    />
                                                    
                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $typeCoin->id }}"
                                                        title="{{ __('Eliminar Tipo de Moneda') }}"
                                                        content="{{ __('¿Estás seguro de que deseas eliminar este tipo de moneda?') }}"
                                                        formId="delete-form-{{ $typeCoin->id }}"
                                                        formAction="{{ route('admin.type-coins.destroy', $typeCoin->id) }}"
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
            </div>
        </div>
    </div>
</div>
@endsection