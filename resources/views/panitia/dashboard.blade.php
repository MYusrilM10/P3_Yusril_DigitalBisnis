@extends('layouts.panitia')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-2xl text-green-700 font-bold">{{ session('success') }}</div>
@endif

<div class="flex flex-wrap justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-4xl font-extrabold">{{ $org->name }}</h1>
        <p class="text-slate-500 mt-1">Dashboard Panitia • {{ ucfirst($org->type) }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-slate-500 font-bold uppercase">Total Event</p>
        <p class="text-3xl font-black mt-2 text-indigo-600">{{ $totalEvents }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $activeEvents }} aktif</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-slate-500 font-bold uppercase">Tiket Terjual</p>
        <p class="text-3xl font-black mt-2 text-green-600">{{ $totalTicketsSold }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-slate-500 font-bold uppercase">Total Revenue</p>
        <p class="text-3xl font-black mt-2 text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <p class="text-sm text-slate-500 font-bold uppercase">Saldo Payout</p>
        <p class="text-3xl font-black mt-2 text-yellow-600">Rp {{ number_format($pendingPayout, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 max-w-2xl">
    <a href="{{ route('panitia.events.create', $org->slug) }}" class="bg-indigo-600 text-white p-4 rounded-2xl text-center font-bold hover:bg-indigo-700 flex items-center justify-center gap-2">
        <i class="fa-solid fa-plus"></i> Buat Event Baru
    </a>
    <a href="{{ route('panitia.events.index', $org->slug) }}" class="bg-white border-2 border-slate-200 p-4 rounded-2xl text-center font-bold hover:border-indigo-600 flex items-center justify-center gap-2">
        <i class="fa-solid fa-calendar-days"></i> Lihat Daftar Event
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Events -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <h2 class="text-xl font-black mb-4 flex items-center gap-2">
            <i class="fa-solid fa-trophy text-yellow-500"></i> Event Terlaris
        </h2>
        @forelse($topEvents as $event)
        <div class="flex justify-between items-center py-3 border-b last:border-0">
            <div>
                <p class="font-bold">{{ $event->title }}</p>
                <p class="text-xs text-slate-400">{{ $event->transactions_count }} tiket terjual</p>
            </div>
            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 text-sm font-bold">Lihat →</a>
        </div>
        @empty
        <p class="text-slate-400 text-center py-6">Belum ada event</p>
        @endforelse
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <h2 class="text-xl font-black mb-4 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-green-600"></i> Transaksi Terbaru
        </h2>
        @forelse($recentTransactions as $trx)
        <div class="flex justify-between items-center py-3 border-b last:border-0">
            <div>
                <p class="font-bold text-sm">{{ $trx->customer_name }}</p>
                <p class="text-xs text-slate-400">{{ $trx->event->title ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-green-600">Rp {{ number_format($trx->net_income, 0, ',', '.') }}</p>
                <p class="text-xs {{ $trx->status === 'success' ? 'text-green-500' : 'text-yellow-500' }}">{{ $trx->status }}</p>
            </div>
        </div>
        @empty
        <p class="text-slate-400 text-center py-6">Belum ada transaksi</p>
        @endforelse
    </div>
</div>
@endsection
