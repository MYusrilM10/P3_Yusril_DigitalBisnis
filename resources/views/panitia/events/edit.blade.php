@extends('layouts.panitia')

@section('content')
<x-back-button :href="route('panitia.events.index', $org->slug)" label="Kembali ke Daftar Event" />
<h1 class="text-3xl font-extrabold mb-8">Edit Event</h1>
    <form action="{{ route('panitia.events.update', [$org->slug, $event->id]) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border p-8 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-bold mb-2">Kategori *</label>
            <select name="category_id" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ $event->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold mb-2">Judul *</label>
            <input type="text" name="title" required value="{{ $event->title }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">{{ $event->description }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-2">Tanggal *</label>
                <input type="datetime-local" name="date" required value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Lokasi *</label>
                <input type="text" name="location" required value="{{ $event->location }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-2">Harga (Rp) *</label>
                <input type="number" name="price" required min="0" value="{{ $event->price }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Stok *</label>
                <input type="number" name="stock" required min="1" value="{{ $event->stock }}" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
        </div>
        @if($event->poster_path)
        <div>
            <p class="text-sm font-bold mb-2">Poster Saat Ini:</p>
            <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-32 h-32 object-cover rounded-xl">
        </div>
        @endif
        <div>
            <label class="block text-sm font-bold mb-2">Ganti Poster (opsional)</label>
            <input type="file" name="poster" accept="image/jpeg,image/png" class="w-full">
        </div>
        <button class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold">Simpan Perubahan</button>
    </form>
@endsection
