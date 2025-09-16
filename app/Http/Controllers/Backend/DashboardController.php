<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Charts\PostChartService;
use App\Services\Charts\UserChartService;
use App\Services\LanguageService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserChartService $userChartService,
        private readonly LanguageService $languageService,
        private readonly PostChartService $postChartService
    ) {
    }

    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['dashboard.view']);

        return view(
            'backend.pages.dashboard.index',
            [
                'total_boxes' => number_format(\App\Models\Box::count()),
                'total_inventories' => number_format(\App\Models\Inventory::count()),
                'total_users' => number_format(User::count()),
                'total_roles' => number_format(Role::count()),
                'total_sales' => number_format(\App\Models\Sale::count()),
                'total_permissions' => number_format(Permission::count()),
                'languages' => [
                        'total' => number_format(count($this->languageService->getLanguages())),
                        'active' => number_format(count($this->languageService->getActiveLanguages())),
                    ],
                'user_growth_data' => $this->userChartService->getClientGrowthData(
                    request()->get('chart_filter_period', 'last_12_months')
                )->getData(true),
                'user_history_data' => $this->userChartService->getUserHistoryData(),
                'post_stats' => $this->postChartService->getInventoryActivityData(
                    request()->get('post_chart_filter_period', 'last_6_months')
                ),
                'breadcrumbs' => [
                    'title' => __('Panel de control'),
                    'show_home' => false,
                    'show_current' => true,
                ]
            ]
        );
    }
}
