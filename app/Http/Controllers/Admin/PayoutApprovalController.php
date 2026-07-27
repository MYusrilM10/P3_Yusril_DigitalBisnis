<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutApprovalController extends Controller
{
    public function index()
    {
        $payouts = Payout::with(['organization', 'requester'])
            ->latest()
            ->paginate(20);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function pending()
    {
        $payouts = Payout::with(['organization', 'requester'])
            ->where('status', 'requested')
            ->latest()
            ->paginate(20);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function approve($id)
    {
        $payout = Payout::findOrFail($id);
        $payout->update([
            'status' => 'paid',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);
        return back()->with('success', 'Payout disetujui dan di-set ke PAID.');
    }

    public function reject(Request $request, $id)
    {
        $payout = Payout::findOrFail($id);
        $data = $request->validate(['reason' => 'nullable|string|max:500']);

        $payout->update([
            'status' => 'rejected',
            'notes' => $data['reason'] ?? $payout->notes,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // Lepaskan transactions dari payout ini
        $payout->transactions()->update(['payout_id' => null]);

        return back()->with('success', 'Payout ditolak.');
    }
}
