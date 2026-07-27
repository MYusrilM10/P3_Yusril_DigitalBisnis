@extends('layouts.panitia')

@section('content')<x-back-button :href="route('panitia.dashboard', $org->slug)" label="Kembali ke Dashboard" />    <h1 class="text-3xl font-extrabold mb-2">Payout</h1>
    <p class="text-slate-500 mb-8">Saldo tersedia: <strong class="text-yellow-600">Rp {{ number_format($availableBalance, 0, ',', '.') }}</strong></p>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 font-bold">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
        <h2 class="text-lg font-black mb-4">Request Payout Baru</h2>
        <form action="{{ route('panitia.payouts.store', $org->slug) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" required min="1" max="{{ $availableBalance }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                <p class="text-xs text-slate-400 mt-1">Maksimal: Rp {{ number_format($availableBalance, 0, ',', '.') }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Periode Mulai (opsional)</label>
                    <input type="date" name="period_start" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Periode Akhir (opsional)</label>
                    <input type="date" name="period_end" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Catatan (opsional)</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none"></textarea>
            </div>
            <button class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Kirim Request
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <h2 class="text-lg font-black p-6 border-b">Riwayat Payout</h2>
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-4 font-bold text-sm">Tanggal</th>
                    <th class="text-left p-4 font-bold text-sm">Jumlah</th>
                    <th class="text-left p-4 font-bold text-sm">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $p)
                <tr class="border-t">
                    <td class="p-4 text-sm">{{ $p->requested_at?->format('d M Y H:i') ?? $p->created_at->format('d M Y') }}</td>
                    <td class="p-4 text-sm font-bold">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-{{ $p->statusColor() }}-100 text-{{ $p->statusColor() }}-700">
                            {{ $p->statusLabel() }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="p-8 text-center text-slate-400">Belum ada payout</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $payouts->links() }}</div>
@endsection
