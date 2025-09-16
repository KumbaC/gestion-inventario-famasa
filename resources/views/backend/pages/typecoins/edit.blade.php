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
                    <form action="{{ route('admin.type-coins.update', $typeCoin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre de la moneda') }}</label>
                                <input type="text" name="name" id="name" value="{{ $typeCoin->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>
                            <div>
                                <label for="symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Abreviatura (Simbolo de la moneda)') }}</label>
                                <input type="text" name="symbol" id="symbol" value="{{ $typeCoin->symbol }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>
                           
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Estado') }}</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ $typeCoin->is_active ? 'selected' : '' }}>{{ __('Activo') }}</option>
                                <option value="0" {{ !$typeCoin->is_active ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                            </select>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Descripción') }}</label>
                                <textarea name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>{{ $typeCoin->description }}</textarea>
                            </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary">{{ __('Actualizar') }}</button>
                        </div>
                    </form>
                </div>



            </div>
        </div>
    </div>
@endsection