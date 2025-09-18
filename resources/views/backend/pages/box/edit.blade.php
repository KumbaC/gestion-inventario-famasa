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
                    <form action="{{ route('admin.box.update', $box->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Nombre') }}</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $box->name) }}" required autofocus placeholder="{{ __('Enter Full Name') }}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            </div>
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Monto de la caja') }}</label>
                                <input type="number" name="amount" id="amount" value="{{ old('amount', $box->amount) }}" required placeholder="{{ __('Enter Amount') }}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            </div>
                            <div>
                                <label for="type_coin_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Tipo de moneda') }}</label>
                                <select name="type_coin_id" id="type_coin_id" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="">{{ __('Seleccione una moneda') }}</option>
                                    @foreach ($type_coins as $typeCoin)
                                        <option value="{{ $typeCoin->id }}" {{ $typeCoin->id == $box->type_coin_id ? 'selected' : '' }}>
                                            {{ $typeCoin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Estado') }}</label>
                                <select name="is_active" id="is_active" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="1" {{ $box->is_active == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                                    <option value="0" {{ $box->is_active == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                                </select>
                            </div>

                        </div>
                        <div class="mt-6 flex justify-start gap-4">
                            <button type="submit" class="btn-primary">{{ __('Actualizar') }}</button>
                            <a href="{{ route('admin.box.index') }}" class="btn-default">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any JavaScript functionality if needed}

        // For example, you can initialize tooltips, modals, etc.
        // Example: Initialize Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>