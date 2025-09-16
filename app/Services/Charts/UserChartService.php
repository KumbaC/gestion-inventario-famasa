<?php

declare(strict_types=1);

namespace App\Services\Charts;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class UserChartService extends ChartService
{
    public function getClientGrowthData(string $period = 'last_12_months'): JsonResponse
    {
        [$startDate, $endDate] = $this->getDateRange($period);

        // Solo crecimiento mensual
        $format = 'M Y';
        $dbFormat = 'Y-m';
        $intervalMethod = 'addMonth';

        $labels = $this->generateLabels($startDate, $endDate, $format, $intervalMethod);

        // Usar el modelo Client
        $clientGrowth = \App\Models\Client::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(id) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $formattedData = $labels->mapWithKeys(function ($label) use ($clientGrowth, $format, $dbFormat) {
            $dbKey = Carbon::createFromFormat($format, $label)->format($dbFormat);
            return [$label => $clientGrowth[$dbKey] ?? 0];
        });

        return response()->json([
            'labels' => $formattedData->keys()->toArray(),
            'data' => $formattedData->values()->toArray(),
        ]);
    }

    /**
     * Get user history data for the pie chart
     *
     * @return array
     */
    public function getUserHistoryData(): array
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $totalUsers = User::count();

        // Get new users count (last 30 days).
        $newUsers = User::where('created_at', '>=', $thirtyDaysAgo)->count();

        return [
            'new_users' => $newUsers,
            'old_users' => $totalUsers - $newUsers,
            'total_users' => $totalUsers,
        ];
    }

    private function fetchUserGrowthData(Carbon $startDate, Carbon $endDate, bool $isLessThanMonth): \Illuminate\Support\Collection
    {
        $selectRaw = $isLessThanMonth
            ? 'DATE(created_at) as day, COUNT(id) as total'
            : 'DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(id) as total';

        $groupBy = $isLessThanMonth ? 'day' : 'month';

        return User::selectRaw($selectRaw)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->pluck('total', $groupBy);
    }
}
