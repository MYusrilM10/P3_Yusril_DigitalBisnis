<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        // 30 hari
        $last30days = $org->transactions()
            ->where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(net_income) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 12 bulan
        $last12months = $org->transactions()
            ->where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(net_income) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Transactions
        $transactions = $org->transactions()
            ->with('event')
            ->latest()
            ->paginate(20);

        return view('panitia.analytics', compact('org', 'last30days', 'last12months', 'transactions'));
    }
}
