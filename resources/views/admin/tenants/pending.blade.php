@extends('layouts.admin')
@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Tenant Pending Approval</h2>
            <p class="text-gray-500 text-sm mt-1">Verifikasi dan setujui pendaftar kepanitiaan baru.</p>
        </div>
        <a href="{{ route('admin.tenants.index') }}" class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-5 py-2.5 rounded-lg font-semibold hover:from-gray-200 hover:to-gray-300 transition-all duration-300 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
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
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Organisasi</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($organizations as $org)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 text-gray-500 font-semibold">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 group-hover:text-indigo-600 transition">{{ $org->name }}</div>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($org->description, 60) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-700 font-medium text-sm">{{ $org->email }}</div>
                                <p class="text-sm text-gray-500 mt-1">{{ $org->phone }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">
                                    {{ ucfirst($org->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.tenants.approve', $org->id) }}" method="POST" onsubmit="return confirm('Setujui pendaftar ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 hover:shadow-md transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tenants.reject', $org->id) }}" method="POST" onsubmit="return confirm('Tolak pendaftar ini?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 hover:shadow-md transition-all duration-200 transform hover:scale-105">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-gray-500 font-medium text-lg">Tidak ada tenant pending</p>
                                    <p class="text-gray-400 text-sm mt-1">Semua pendaftar sudah diverifikasi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $organizations->links() }}</div>
</div>
@endsection
