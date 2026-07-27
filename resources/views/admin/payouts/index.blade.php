@extends('layouts.admin')
@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Approval Payout</h2>
            <p class="text-gray-500 text-sm mt-1">Approve atau tolak permintaan pencairan dana dari kepanitiaan.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Organisasi</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Bank</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payouts as $p)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $p->requested_at?->format('d M Y') ?? $p->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 group-hover:text-indigo-600 transition">{{ $p->organization->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-800 font-semibold">Rp {{ number_format($p->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->organization)
                                    <div class="font-semibold text-gray-800 text-sm">{{ $p->organization->bank_name ?? '-' }}</div>
                                    <p class="text-sm text-gray-500 mt-1">{{ $p->organization->bank_account_number ?? '-' }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php $color = $p->statusColor(); @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-{{ $color }}-100 text-{{ $color }}-700">
                                    {{ $p->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'requested')
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.payouts.approve', $p->id) }}" method="POST" onsubmit="return confirm('Setujui & tandai PAID?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 hover:shadow-md transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.payouts.reject', $p->id) }}" method="POST" onsubmit="return confirm('Tolak payout ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 hover:shadow-md transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <p class="text-gray-500 font-medium text-lg">Belum ada data payout</p>
                                    <p class="text-gray-400 text-sm mt-1">Permintaan payout akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $payouts->links() }}</div>
</div>
@endsection
