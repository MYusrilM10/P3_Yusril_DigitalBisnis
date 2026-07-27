@extends('layouts.panitia')

@section('content')
<x-back-button :href="route('panitia.dashboard', $org->slug)" label="Kembali ke Dashboard" />
<h1 class="text-3xl font-extrabold mb-8">Kelola Staff</h1>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-2xl text-green-700 font-bold flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
        <h2 class="text-lg font-black mb-4">Undang Anggota Baru</h2>
        <form action="{{ route('panitia.staff.invite', $org->slug) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="name" required placeholder="Nama" class="px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                <input type="email" name="email" required placeholder="Email" class="px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                <select name="role" required class="px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                    <option value="owner">Owner</option>
                </select>
            </div>
            <button class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Tambah Anggota
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <h2 class="text-lg font-black p-6 border-b">Daftar Anggota ({{ $members->count() }})</h2>
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-4 font-bold text-sm">Nama</th>
                    <th class="text-left p-4 font-bold text-sm">Email</th>
                    <th class="text-left p-4 font-bold text-sm">Role</th>
                    <th class="text-left p-4 font-bold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $m)
                <tr class="border-t">
                    <td class="p-4 text-sm font-bold">{{ $m->name }}</td>
                    <td class="p-4 text-sm">{{ $m->email }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $m->pivot->role === 'owner' ? 'bg-purple-100 text-purple-700' : ($m->pivot->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                            {{ ucfirst($m->pivot->role) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <form action="{{ route('panitia.staff.destroy', [$org->slug, $m->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
