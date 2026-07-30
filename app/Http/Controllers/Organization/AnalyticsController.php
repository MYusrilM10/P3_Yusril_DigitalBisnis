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

        // 12 bulan (di-group di PHP, bukan raw SQL, biar jalan di SQLite maupun MySQL)
        $last12months = $org->transactions()
            ->where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->get(['created_at', 'net_income'])
            ->groupBy(fn ($trx) => $trx->created_at->format('Y-m'))
            ->map(function ($group, $key) {
                [$year, $month] = explode('-', $key);

                return (object) [
                    'year' => (int) $year,
                    'month' => (int) $month,
                    'count' => $group->count(),
                    'revenue' => $group->sum('net_income'),
                ];
            })
            ->sortBy(fn ($row) => sprintf('%04d%02d', $row->year, $row->month))
            ->values();

        // Transactions
        $transactions = $org->transactions()
            ->with('event')
            ->latest()
            ->paginate(20);

        return view('panitia.analytics', compact('org', 'last30days', 'last12months', 'transactions'));
    }
}
