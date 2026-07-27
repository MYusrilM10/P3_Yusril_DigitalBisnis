@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-extrabold mb-2">Daftar Penyelenggara</h1>
    <p class="text-slate-500 mb-8">Semua kepanitiaan yang aktif di AmikomEventHub</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($organizations as $org)
        <a href="{{ route('panitia.show', $org->slug) }}" class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-lg hover:border-indigo-600 transition">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl text-2xl font-black mb-3">
                {{ strtoupper(substr($org->name, 0, 1)) }}
            </div>
            <h3 class="text-lg font-black mb-1">{{ $org->name }}</h3>
            <p class="text-xs text-slate-400 mb-2">{{ ucfirst($org->type) }}</p>
            <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $org->description }}</p>
            <p class="text-xs text-indigo-600 font-bold">{{ $org->events_count }} event</p>
        </a>
        @empty
        <p class="col-span-3 text-center text-slate-400 py-12">Belum ada kepanitiaan</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $organizations->links() }}</div>
</main>
@endsection
