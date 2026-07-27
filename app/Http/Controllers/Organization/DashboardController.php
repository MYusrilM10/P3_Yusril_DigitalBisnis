<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index($slug)
    {
        $org = Organization::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Statistik utama
        $totalEvents = $org->events()->count();
        $activeEvents = $org->events()->where('date', '>=', now())->count();
        $totalTicketsSold = $org->totalTicketsSold();
        $totalRevenue = $org->totalRevenue();
        $pendingPayout = $org->pendingPayoutAmount();

        // 7 event terlaris
        $topEvents = $org->events()
            ->withCount(['transactions' => function ($q) {
                $q->where('status', 'success');
            }])
            ->orderBy('transactions_count', 'desc')
            ->limit(5)
            ->get();

        // Revenue 7 hari terakhir (chart data)
        $revenueChart = $org->transactions()
            ->where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(net_income) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent transactions
        $recentTransactions = $org->transactions()
            ->with('event')
            ->latest()
            ->limit(10)
            ->get();

        return view('panitia.dashboard', compact(
            'org', 'totalEvents', 'activeEvents',
            'totalTicketsSold', 'totalRevenue', 'pendingPayout',
            'topEvents', 'revenueChart', 'recentTransactions'
        ));
    }
}
