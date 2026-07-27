@extends('layouts.panitia')

@section('content')<x-back-button :href="route('panitia.dashboard', $org->slug)" label="Kembali ke Dashboard" />    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-extrabold">Event {{ $org->name }}</h1>
        <a href="{{ route('panitia.events.create', $org->slug) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold">+ Buat Event</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 font-bold">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-4 font-bold text-sm">Event</th>
                    <th class="text-left p-4 font-bold text-sm">Tanggal</th>
                    <th class="text-left p-4 font-bold text-sm">Harga</th>
                    <th class="text-left p-4 font-bold text-sm">Stock</th>
                    <th class="text-left p-4 font-bold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="border-t">
                    <td class="p-4">
                        <p class="font-bold">{{ $event->title }}</p>
                        <p class="text-xs text-slate-400">{{ $event->location }}</p>
                    </td>
                    <td class="p-4 text-sm">{{ \Carbon\Carbon::parse($event->date)->format('d M Y H:i') }}</td>
                    <td class="p-4 text-sm">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                    <td class="p-4 text-sm">{{ $event->stock }}</td>
                    <td class="p-4">
                        <a href="{{ route('panitia.events.edit', [$org->slug, $event->id]) }}" class="text-indigo-600 text-sm font-bold mr-3">Edit</a>
                        <form action="{{ route('panitia.events.destroy', [$org->slug, $event->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada event</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $events->links() }}</div>
@endsection
