@extends('backend.layouts.app')

@section('title')
   {{ $breadcrumbs['title'] }} | {{ config('app.name') }}
@endsection

@section('before_vite_build')
    <script>
        var userGrowthData = @json($user_growth_data['data']);
        var userGrowthLabels = @json($user_growth_data['labels']);
    </script>
@endsection

@section('admin-content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

        {!! ld_apply_filters('dashboard_after_breadcrumbs', '') !!}

        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 space-y-6">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-4 md:gap-6">
                    {!! ld_apply_filters('dashboard_cards_before_users', '') !!}
                    @include('backend.pages.dashboard.partials.card', [
                        'icon_svg' => asset('images/icons/user.svg'),
                        'label' => __('Usuarios'),
                        'value' => $total_users,
                        'bg' => '#437066',
                        'class' => 'bg-white',
                        'url' => route('admin.users.index'),
                        'enable_full_div_click' => true,
                    ])
                    {!! ld_apply_filters('dashboard_cards_after_roles', '') !!}
                    @include('backend.pages.dashboard.partials.card', [
                        'icon'  => 'bi bi-bag-check',
                        'label' => __('Ventas'),
                        'value' => $total_sales,
                        'bg'    => '#00D7FF',
                        'class' => 'bg-white',
                        'url'   => route('admin.sales.index'),
                        'enable_full_div_click' => true,
                    ])
                    {!! ld_apply_filters('dashboard_cards_after_roles', '') !!}
                    @include('backend.pages.dashboard.partials.card', [
                        'icon' => 'bi bi-archive',
                        'label' => __('Stock del Inventario'),
                        'value' => $total_inventories,
                        'bg' => '#FF4D96',
                        'class' => 'bg-white',
                        'url' => route('admin.inventories.index'),
                        'enable_full_div_click' => true,
                    ])
                    {!! ld_apply_filters('dashboard_cards_after_permissions', '') !!}
                    @include('backend.pages.dashboard.partials.card', [
                        'icon' => 'bi bi-menu-app-fill',
                        'label' => __('Cajas'),
                        'value' => $total_boxes,
                        'bg' => '#22C55E',
                        'class' => 'bg-white',
                        'url' => route('admin.box.index'),
                        'enable_full_div_click' => true,
                    ])
                    {!! ld_apply_filters('dashboard_cards_after_translations', '') !!}
                </div>
            </div>
        </div>

        {!! ld_apply_filters('dashboard_cards_after', '') !!}

        <div class="mt-6">
            <div class="grid grid-cols-12 gap-4 md:gap-6">
                <div class="col-span-12">
                    <div class="grid grid-cols-12 gap-4 md:gap-6">
                        <div class="col-span-12 md:col-span-8">
                            @include('backend.pages.dashboard.partials.user-growth')
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            @include('backend.pages.dashboard.partials.user-history')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
     {{--    <div class="mt-6">
            <div class="grid grid-cols-12 gap-4 md:gap-6">
                <div class="col-span-12">
                    <div class="grid grid-cols-12 gap-4 md:gap-6">
                        @include('backend.pages.dashboard.partials.post-chart')
                    </div>
                </div>
            </div>
        </div> --}}

        {!! ld_apply_filters('dashboard_after', '') !!}
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush
