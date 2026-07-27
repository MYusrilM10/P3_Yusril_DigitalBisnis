@extends('layouts.app')

@section('title', 'Beri Ulasan - ' . $event->title)

@section('content')
<main class="max-w-2xl mx-auto px-6 py-12">
    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-6">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke detail event
    </a>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl mb-4">
                <i class="fa-solid fa-star text-2xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 mb-2">Beri Ulasan</h1>
            <p class="text-slate-500">Bagaimana pengalaman Anda mengikuti acara ini?</p>
        </div>

        <div class="bg-indigo-50 border-2 border-indigo-100 rounded-2xl p-4 mb-6">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 mb-1">Acara</p>
            <h2 class="text-lg font-black text-slate-900">{{ $event->title }}</h2>
            <p class="text-sm text-slate-500">
                {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }} &middot; {{ $event->location }}
            </p>
            <p class="text-xs text-slate-400 mt-2">Order: <span class="font-mono">{{ $transaction->order_id }}</span></p>
        </div>

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-2xl text-red-700 font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('review.public.submit', $transaction->order_id) }}" method="POST" x-data="{ rating: 0, hover: 0 }" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Rating <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-2 justify-center py-4 bg-slate-50 rounded-2xl">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            @click="rating = {{ $i }}"
                            @mouseenter="hover = {{ $i }}"
                            @mouseleave="hover = 0"
                            class="text-4xl focus:outline-none transition-transform hover:scale-110">
                        <i :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'fa-solid fa-star text-yellow-400' : 'fa-regular fa-star text-slate-300'"></i>
                    </button>
                    @endfor
                </div>
                <p class="text-center text-sm text-slate-500 mt-2">
                    <span x-show="rating === 0">Pilih rating</span>
                    <span x-show="rating === 1" class="font-bold text-red-600">Sangat Buruk</span>
                    <span x-show="rating === 2" class="font-bold text-orange-600">Buruk</span>
                    <span x-show="rating === 3" class="font-bold text-yellow-600">Cukup</span>
                    <span x-show="rating === 4" class="font-bold text-lime-600">Bagus</span>
                    <span x-show="rating === 5" class="font-bold text-green-600">Sangat Bagus</span>
                </p>
                <input type="hidden" name="rating" :value="rating">
                @error('rating') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul (opsional)</label>
                <input type="text" name="title" maxlength="255" placeholder="Cth: Acara yang luar biasa!"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Testimoni (opsional, min 10 karakter)</label>
                <textarea name="review_text" rows="4" minlength="10"
                          placeholder="Ceritakan pengalaman Anda mengikuti acara ini..."
                          class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none"></textarea>
                @error('review_text') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    :disabled="rating === 0"
                    :class="rating === 0 ? 'bg-slate-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 hover:shadow-indigo-300'"
                    class="w-full py-4 text-white rounded-2xl font-black text-lg shadow-xl active:scale-95 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane"></i>
                Kirim Ulasan
            </button>
        </form>
    </div>
</main>
@endsection
