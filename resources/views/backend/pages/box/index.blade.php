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
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">{{ __('Detalles de la Caja') }}</h3>
                <div class="flex gap-2">
                    {{-- @if (auth()->user()->can('box.edit'))
                        <a href="{{ route('admin.box.index', $box->id) }}" class="btn-primary">
                            <i class="bi bi-pencil mr-2"></i>
                            {{ __('Editar') }}
                        </a>
                    @endif --}}
                    <a href="{{ route('admin.box.create') }}" class="btn-default">
                        <i class="bi bi-plus mr-2"></i>
                        {{ __('Agregar') }}
                    </a>
                </div>
            </div>

            <div class="px-5 py-4 sm:px-6 sm:py-5">
                <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                    <table class="table-auto w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">{{ __('Nombre de la caja') }}</th>
                                <th class="px-4 py-2">{{ __('Monto') }}</th>
                                <th class="px-4 py-2">{{ __('Tipo de moneda') }}</th>
                                <th class="px-4 py-2">{{ __('Usuario') }}</th>
                                <th class="px-4 py-2">{{ __('Estado') }}</th>
                                <th class="px-4 py-2">{{ __('Fecha de creación') }}</th>
                                <th class="px-4 py-2">{{ __('Fecha de actualización') }}</th>
                                <th class="px-4 py-2">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($box as $boxes)
                                <tr>
                                    <td class="border px-4 py-2">{{ $boxes->name }}</td>
                                    <td class="border px-4 py-2">{{ $boxes->amount }}</td>
                                    <td class="border px-4 py-2">{{ $boxes->typeCoin->name }}</td>
                                    <td class="border px-4 py-2">{{ $boxes->user->name }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($boxes->is_active)
                                            <span class="text-green-600">{{ __('Activo') }}</span>
                                        @else
                                            <span class="text-red-600">{{ __('Inactivo') }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ $boxes->created_at ? $boxes->created_at->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ $boxes->updated_at ? $boxes->updated_at->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        <div class="flex gap-2 text-center justify-center">
                                           @if (auth()->user()->can('box.edit'))
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
                                                                {{ __('Editar Cajas') }}
                                                            </h2>
                                                            <p class="text-gray-600 dark:text-gray-400 mt-2">
                                                                {{ __('¿Estás seguro de que deseas editar esta caja?') }}
                                                            </p>

                                                            <div class="flex justify-end gap-2 mt-4">
                                                                <button 
                                                                    @click="editModalOpen = false" 
                                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                                                                >
                                                                    {{ __('No, cancelar') }}
                                                                </button>

                                                                <a 
                                                                    href="{{ route('admin.box.edit', $boxes->id) }}" 
                                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800"
                                                                >
                                                                    {{ __('Sí, Editar') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('box.delete'))
                                                <div x-data="{ deleteModalOpen: false }">
                                                    <x-buttons.action-item
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash"
                                                        :label="__('')"
                                                        class="text-red-600 hover:underline"
                                                    />

                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $boxes->id }}"
                                                        title="{{ __('Eliminar Caja') }}"
                                                        type="modal-trigger"
                                                        modal-target="deleteModalOpen"
                                                        icon="trash" 
                                                        :label="__('')" 
                                                        class="text-red-600 hover:underline"
                                                    />
                                                    
                                                    <x-modals.confirm-delete
                                                        id="delete-modal-{{ $boxes->id }}"
                                                        title="{{ __('Eliminar Caja') }}"
                                                        content="{{ __('¿Estás seguro de que deseas eliminar esta caja?') }}"
                                                        formId="delete-form-{{ $boxes->id }}"
                                                        formAction="{{ route('admin.box.destroy', $boxes->id) }}"
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
                <!-- Box Content -->
                <div class="prose dark:prose-invert">
                    {!! $box->links() !!}
                </div>
                
            </div>
            <!-- Box Content -->
            
    </div>
</div>

@endsection
