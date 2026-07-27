@extends('layouts.panitia')

@section('content')
<x-back-button :href="route('panitia.dashboard', $org->slug)" label="Kembali ke Dashboard" />
        <!-- 30 days revenue chart -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-black mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-indigo-600"></i> Revenue 7 Hari Terakhir
            </h2>
            <div class="space-y-2">
                @forelse($last30days as $row)
                <div class="flex items-center gap-3">
                    <span class="text-xs w-20 text-slate-500">{{ \Carbon\Carbon::parse($row->date)->format('d M') }}</span>
                    <div class="flex-1 bg-slate-100 rounded-full h-6 relative">
                        <div class="bg-indigo-600 h-6 rounded-full" style="width: {{ min(100, $row->revenue / 100000) }}%"></div>
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold">Rp {{ number_format($row->revenue, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <!-- 12 months chart -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-black mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-green-600"></i> Revenue 12 Bulan Terakhir
            </h2>
            <div class="space-y-2">
                @forelse($last12months as $row)
                <div class="flex items-center gap-3">
                    <span class="text-xs w-20 text-slate-500">{{ sprintf('%04d-%02d', $row->year, $row->month) }}</span>
                    <div class="flex-1 bg-slate-100 rounded-full h-6 relative">
                        <div class="bg-green-600 h-6 rounded-full" style="width: {{ min(100, $row->revenue / 1000000) }}%"></div>
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold">Rp {{ number_format($row->revenue, 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Transactions table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <h2 class="text-lg font-black p-6 border-b flex items-center gap-2">
            <i class="fa-solid fa-receipt text-indigo-600"></i> Transaksi
        </h2>
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-4 font-bold text-sm">Tanggal</th>
                    <th class="text-left p-4 font-bold text-sm">Customer</th>
                    <th class="text-left p-4 font-bold text-sm">Event</th>
                    <th class="text-left p-4 font-bold text-sm">Net Income</th>
                    <th class="text-left p-4 font-bold text-sm">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-t">
                    <td class="p-4 text-sm">{{ $trx->created_at->format('d M Y H:i') }}</td>
                    <td class="p-4 text-sm">{{ $trx->customer_name }}</td>
                    <td class="p-4 text-sm">{{ $trx->event->title ?? '-' }}</td>
                    <td class="p-4 text-sm font-bold text-green-600">Rp {{ number_format($trx->net_income, 0, ',', '.') }}</td>
                    <td class="p-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $trx->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $trx->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $transactions->links() }}</div>
@endsection
