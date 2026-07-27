<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Payout;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $payouts = $org->payouts()->latest()->paginate(15);
        $availableBalance = $org->pendingPayoutAmount();

        return view('panitia.payouts', compact('org', 'payouts', 'availableBalance'));
    }

    public function store(Request $request, $slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'notes' => 'nullable|string|max:500',
        ]);

        $available = $org->pendingPayoutAmount();
        if ($data['amount'] > $available) {
            return back()->withErrors(['amount' => 'Jumlah melebihi saldo tersedia (Rp ' . number_format($available, 0, ',', '.') . ')']);
        }

        DB::transaction(function () use ($data, $org) {
            $payout = $org->payouts()->create([
                'amount' => $data['amount'],
                'period_start' => $data['period_start'] ?? null,
                'period_end' => $data['period_end'] ?? null,
                'status' => 'requested',
                'notes' => $data['notes'] ?? null,
                'requested_by' => auth()->id(),
                'requested_at' => now(),
            ]);

            // Tag transactions ke payout ini
            $transactions = $org->transactions()
                ->where('status', 'success')
                ->whereNull('payout_id')
                ->orderBy('created_at')
                ->limit(1000)
                ->get();

            $remaining = $data['amount'];
            foreach ($transactions as $trx) {
                if ($remaining <= 0) break;
                $trx->payout_id = $payout->id;
                $trx->save();
                $remaining -= $trx->net_income;
            }
        });

        return redirect()->route('panitia.payouts', $org->slug)
            ->with('success', 'Payout request berhasil! Menunggu persetujuan superadmin.');
    }
}
