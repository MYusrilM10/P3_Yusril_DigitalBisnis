@extends('layouts.app')

@section('content')
@php
    // Aggregate rating & reviews across all events of this org
    $eventIds = $org->events->pluck('id');
    $allReviews = \App\Models\Review::with('event')
        ->whereIn('event_id', $eventIds)
        ->where('is_verified_purchase', true)
        ->orderBy('created_at', 'desc')
        ->get();
    $avgRating = $allReviews->count() > 0 ? round($allReviews->avg('rating'), 1) : 0;
    $totalReviews = $allReviews->count();
@endphp

<main class="max-w-5xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-sm border p-8 mb-8">
        <div class="flex items-center gap-6 mb-4">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 text-indigo-600 rounded-3xl text-4xl font-black">
                {{ strtoupper(substr($org->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-extrabold">{{ $org->name }}</h1>
                <p class="text-slate-500">{{ ucfirst($org->type) }}</p>
            </div>
            @if($totalReviews > 0)
            <div class="text-right">
                <div class="flex items-center gap-1 text-yellow-400 justify-end">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= round($avgRating) ? '' : 'opacity-25' }}"></i>
                    @endfor
                </div>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $avgRating }} <span class="text-sm text-slate-500 font-medium">/ 5</span></p>
                <p class="text-xs text-slate-500">{{ $totalReviews }} ulasan</p>
            </div>
            @endif
        </div>
        <p class="text-slate-600">{{ $org->description }}</p>
    </div>

    @if($totalReviews > 0)
    <div class="bg-white rounded-3xl shadow-sm border p-8 mb-8">
        <h2 class="text-2xl font-black mb-6 flex items-center gap-2">
            <i class="fa-solid fa-comments text-indigo-600"></i>
            Ulasan Peserta
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($allReviews->take(6) as $r)
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <div class="flex items-center gap-1 text-yellow-400 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star text-xs {{ $i <= $r->rating ? '' : 'opacity-25' }}"></i>
                    @endfor
                </div>
                @if($r->title)<p class="font-black text-slate-900 text-sm mb-1">{{ $r->title }}</p>@endif
                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-3">"{{ $r->review_text }}"</p>
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700">{{ $r->customer_name ?? ($r->user->name ?? 'Anonim') }}</span>
                    <span class="text-slate-400">{{ $r->event->title ?? '' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <h2 class="text-2xl font-black mb-4">Event dari {{ $org->name }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($org->events as $event)
        <a href="{{ route('events.show', $event->id) }}" class="bg-white rounded-2xl shadow-sm border overflow-hidden hover:shadow-lg transition">
            <img src="{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://placehold.co/400x200' }}" class="w-full h-40 object-cover">
            <div class="p-4">
                <h3 class="font-bold mb-1">{{ $event->title }}</h3>
                <p class="text-xs text-slate-400 mb-2">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} • {{ $event->location }}</p>
                <p class="font-black text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
            </div>
        </a>
        @empty
        <p class="col-span-3 text-center text-slate-400 py-12">Belum ada event</p>
        @endforelse
    </div>
</main>
@endsection
