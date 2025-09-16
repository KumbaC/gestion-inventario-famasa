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
                    <form action="{{ route('admin.clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nombre') }}</label>
                                <input type="text" name="name" id="name" value="{{ $client->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>

                            <div>
                                <label for="identification" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Identificación') }}</label>
                                <input type="text" name="identification" id="identification" value="{{ $client->identification }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Teléfono') }}</label>
                                <input type="text" name="phone" id="phone" value="{{ $client->phone }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300" required>
                            </div>

                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn-primary">{{ __('Modificar Cliente') }}</button>
                            <a href="{{ route('admin.clients.index') }}" class="btn-default">{{ __('Cancelar') }}</a>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
</div>
@endsection