@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Tiket Saya</h1>
        <p class="text-gray-600">Kelola tiket acara Anda dan berikan review</p>
    </div>

    @if($transactions->count() > 0)
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 items-center">
                        <!-- Event Info -->
                        <div class="md:col-span-2">
                            @if($transaction->event->poster_path)
                                <img src="{{ asset('storage/' . $transaction->event->poster_path) }}" 
                                     alt="{{ $transaction->event->title }}" 
                                     class="w-16 h-20 object-cover rounded float-left mr-4">
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $transaction->event->title }}</h3>
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fa-solid fa-calendar w-4 h-4"></i>
                                    {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fa-solid fa-location-dot w-4 h-4"></i>
                                    {{ $transaction->event->location }}
                                </p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="text-center">
                            <div class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($transaction->event_finished)
                                    bg-green-100 text-green-800
                                @else
                                    bg-blue-100 text-blue-800
                                @endif">
                                @if($transaction->event_finished)
                                    <i class="fa-solid fa-circle-check text-green-500"></i> Acara Selesai
                                @else
                                    ⏱ Acara Berlangsung
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 justify-end flex-wrap">
                            <a href="{{ route('events.show', $transaction->event) }}" 
                               class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-sm font-medium">
                                Lihat Acara
                            </a>

                            @if($transaction->can_review)
                                @if($transaction->has_review)
                                    <a href="{{ route('reviews.edit', $transaction->review) }}" 
                                       class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition text-sm font-medium">
                                        Edit Review
                                    </a>
                                @else
                                    <a href="{{ route('reviews.create', $transaction->event) }}" 
                                       class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                                        <i class="fa-solid fa-star text-yellow-400"></i> Beri Rating
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Review Preview -->
                    @if($transaction->has_review)
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2"><strong>Review Anda:</strong></p>
                            <div class="flex gap-2 items-center mb-2">
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $transaction->review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold">{{ $transaction->review->rating }}/5</span>
                            </div>
                            @if($transaction->review->title)
                                <p class="text-sm font-semibold text-gray-800 mb-1">{{ $transaction->review->title }}</p>
                            @endif
                            @if($transaction->review->review_text)
                                <p class="text-sm text-gray-700">{{ Str::limit($transaction->review->review_text, 100) }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <div class="text-6xl mb-4"><i class="fa-solid fa-ticket text-indigo-200"></i></div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Belum Ada Tiket</h2>
            <p class="text-gray-600 mb-6">Anda belum membeli tiket apapun. Jelajahi acara menarik sekarang!</p>
            <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                Jelajahi Acara
            </a>
        </div>
    @endif
</div>
@endsection
