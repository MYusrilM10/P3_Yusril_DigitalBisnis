@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Left: Poster --}}
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/200x600' }}" alt="{{ $event->title }}"
                    class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
                <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        @if($event->organization?->logo_path)
                            <img src="{{ asset('storage/' . $event->organization->logo_path) }}"
                                 alt="{{ $event->organization->name }}"
                                 class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                {{ strtoupper(substr($event->organization->name ?? 'AmikomEventHub', 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-800">{{ $event->organization->name ?? 'AmikomEventHub' }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $event->organization && $event->organization->status === 'active' ? 'Verified Organizer' : 'Penyelenggara' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Details --}}
        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span
                    class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">{{ $event->category->name }}</span>
                <h1 class="text-4xl md:text-5xl font-black leading-tight">{{ $event->title }}</h1>
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar w-5 h-5 text-indigo-600"></i>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot w-5 h-5 text-indigo-600"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed">
                    {{ $event->description }}
                </p>
            </div>

            <div
                class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        <h2 class="text-5xl font-black">Rp {{ number_format($event->price, 0, ',', '.') }} <span class="text-lg font-medium text-indigo-200">/
                                orang</span></h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <i class="fa-solid fa-ticket w-5 h-5"></i>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ url('checkout/' . $event->id) }}"
                            class="inline-flex items-center gap-2 px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            <i class="fa-solid fa-shopping-cart w-6 h-6"></i>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                {{-- Decoration --}}
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
                <ul class="space-y-3 text-slate-500">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check w-5 h-5 text-green-500 mt-1"></i>
                        E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check w-5 h-5 text-green-500 mt-1"></i>
                        Tiket dapat discan di pintu masuk (Check-in).
                    </li>
                    <li class="flex items-start gap-2 text-rose-500">
                        <i class="fa-solid fa-circle-xmark w-5 h-5 text-rose-500 mt-1"></i>
                        Tiket yang sudah dibeli tidak dapat direfund.
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Review Section -->
    <section class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Ulasan & Penilaian</h2>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Stats Sidebar -->
                <div class="lg:col-span-1">
                    @include('components.review-stats', ['event' => $event, 'averageRating' => $event->getAverageRating(), 'totalReviews' => $event->getTotalReviews()])
                </div>

                <!-- Reviews List -->
                <div class="lg:col-span-2">
                    @php
                        $reviews = $event->reviews()
                            ->where('is_verified_purchase', true)
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(5);
                    @endphp
                    @include('components.review-list', ['reviews' => $reviews])
                </div>
            </div>
        </div>
    </section>
@endsection