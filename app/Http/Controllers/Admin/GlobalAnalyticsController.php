<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GlobalAnalyticsController extends Controller
{
    public function index()
    {
        $totalOrgs = Organization::count();
        $activeOrgs = Organization::where('status', 'active')->count();
        $totalEvents = \App\Models\Event::count();
        $totalTransactions = Transaction::where('status', 'success')->count();
        $totalRevenue = Transaction::where('status', 'success')->sum('net_income') ?? 0;
        $totalCommission = Transaction::where('status', 'success')->sum('platform_fee') ?? 0;
        $pendingPayouts = \App\Models\Payout::where('status', 'requested')->count();

        // Top 5 org by revenue
        $topOrgs = Organization::withCount(['transactions' => function ($q) {
                $q->where('status', 'success');
            }])
            ->get()
            ->sortByDesc(function ($org) {
                return $org->transactions()->where('status', 'success')->sum('net_income') ?? 0;
            })
            ->take(5);

        // 30 hari terakhir
        $last30days = Transaction::where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(net_income) as revenue, SUM(platform_fee) as commission')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $userGrowthByDate = User::where('role', 'user')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $eventGrowthByDate = Event::where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as growth_date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->pluck('count', 'growth_date');

        $userGrowthFilled = $this->fillDateRange($userGrowthByDate);
        $eventGrowthFilled = $this->fillDateRange($eventGrowthByDate);

        $growthLabels = array_keys($userGrowthFilled);
        $userGrowth = array_values($userGrowthFilled);
        $eventGrowth = array_values($eventGrowthFilled);

        return view('admin.analytics', compact(
            'totalOrgs', 'activeOrgs', 'totalEvents',
            'totalTransactions', 'totalRevenue', 'totalCommission',
            'pendingPayouts', 'topOrgs', 'last30days',
            'growthLabels', 'userGrowth', 'eventGrowth'
        ));
    }

    private function fillDateRange(Collection $countsByDate, int $days = 30): array
    {
        $result = [];
        $cursor = now()->subDays($days - 1)->startOfDay();

        for ($i = 0; $i < $days; $i++) {
            $key = $cursor->toDateString();
            $result[$key] = (int) ($countsByDate[$key] ?? 0);
            $cursor->addDay();
        }

        return $result;
    }
}
