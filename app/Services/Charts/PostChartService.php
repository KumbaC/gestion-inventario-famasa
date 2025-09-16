<?php

declare(strict_types=1);

namespace App\Services\Charts;

use App\Models\Post;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostChartService
{
    /**
     * Get post statistics for the chart
     * 
     * @param string $period The time period for the chart (last_6_months, last_12_months, this_year, etc.)
     * @return array
     */
    public function getInventoryActivityData(string $period = 'last_6_months'): array
    {
        $dateRange = $this->getDateRangeFromPeriod($period);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        $interval = $dateRange['interval'];

        $labels = [];
        $addedData = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            if ($interval === 'month') {
                $labels[] = $currentDate->format('M Y');
                $addedCount = Inventory::whereYear('created_at', $currentDate->year)
                    ->whereMonth('created_at', $currentDate->month)
                    ->count();
                $nextDate = $currentDate->copy()->addMonth();
            } elseif ($interval === 'week') {
                $weekStart = $currentDate->copy()->startOfDay();
                $weekEnd = $currentDate->copy()->addDays(6)->endOfDay();
                $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                $addedCount = Inventory::whereBetween('created_at', [
                        $weekStart->toDateTimeString(),
                        $weekEnd->toDateTimeString()
                    ])
                    ->count();
                $nextDate = $currentDate->copy()->addWeek();
            } else { // day
                $labels[] = $currentDate->format('d M');
                $addedCount = Inventory::whereDate('created_at', $currentDate->toDateString())
                    ->count();
                $nextDate = $currentDate->copy()->addDay();
            }

            $addedData[] = $addedCount;
            $currentDate = $nextDate;
        }

        return [
            'labels' => $labels,
            'added' => $addedData,
        ];
    }
    
    /**
     * Get date range based on period string
     * 
     * @param string $period
     * @return array
     */
    private function getDateRangeFromPeriod(string $period): array
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'last_7_days':
                return [
                    'start' => $now->copy()->subDays(6)->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'interval' => 'day'
                ];
            case 'last_30_days':
                return [
                    'start' => $now->copy()->subDays(29)->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'interval' => 'day'
                ];
            case 'last_3_months':
                return [
                    'start' => $now->copy()->subMonths(2)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'interval' => 'month'
                ];
            case 'last_12_months':
                return [
                    'start' => $now->copy()->subMonths(11)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'interval' => 'month'
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear(),
                    'interval' => 'month'
                ];
            case 'last_year':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear(),
                    'interval' => 'month'
                ];
            case 'last_6_months':
            default:
                return [
                    'start' => $now->copy()->subMonths(5)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'interval' => 'month'
                ];
        }
    }
}
