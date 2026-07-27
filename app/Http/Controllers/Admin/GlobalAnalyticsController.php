<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

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

        return view('admin.analytics', compact(
            'totalOrgs', 'activeOrgs', 'totalEvents',
            'totalTransactions', 'totalRevenue', 'totalCommission',
            'pendingPayouts', 'topOrgs', 'last30days'
        ));
    }
}
