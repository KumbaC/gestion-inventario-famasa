@extends('backend.layouts.app')

@section('title')
    {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('admin-content')

<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
   <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    {!! ld_apply_filters('users_after_breadcrumbs', '') !!}

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Nombre completo') }}</label>
                            <input type="text" name="name" id="name" required autofocus value="{{ old('name') }}" placeholder="{{ __('Ingrese nombre completo') }}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Correo electrónico') }}</label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="{{ __('Ingrese correo electrónico') }}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        </div>
                        <div>
                            <x-inputs.password
                                name="password"
                                label="{{ __('Contraseña') }}"
                                placeholder="{{ __('Ingrese contraseña') }}"
                                required
                            />
                        </div>
                        <div>
                            <x-inputs.password
                                name="password_confirmation"
                                label="{{ __('Confirmar contraseña') }}"
                                placeholder="{{ __('Confirme la contraseña') }}"
                                required
                            />
                        </div>
                        <div>
                            <x-inputs.combobox
                                name="roles[]"
                                label="{{ __('Asignar roles') }}"
                                placeholder="{{ __('Seleccionar roles') }}"
                                :options="collect($roles)->map(fn($name, $id) => ['value' => $name, 'label' => ucfirst($name)])->values()->toArray()"
                                :selected="old('roles', [])"
                                :multiple="true"
                                :searchable="false"
                            />
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-400">{{ __('Nombre de usuario') }}</label>

                            <input type="text" name="username" id="username" required value="{{ old('username') }}" placeholder="{{ __('Ingrese nombre de usuario') }}" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        </div>
                        {!! ld_apply_filters('after_username_field', '', null) !!}
                    </div>
                    <div class="mt-6 flex justify-start gap-4">
                        <button type="submit" class="btn-primary">{{ __('Guardar') }}</button>
                        <a href="{{ route('admin.users.index') }}" class="btn-default">{{ __('Cancelar') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
