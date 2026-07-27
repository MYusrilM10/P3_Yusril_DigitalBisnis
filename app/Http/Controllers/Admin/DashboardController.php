<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])->sum('total_price');

        // 2. Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])->count();

        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())->count();

        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = Transaction::where('status', 'pending')->count();

        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

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

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'growthLabels',
            'userGrowth',
            'eventGrowth'
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
