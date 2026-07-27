@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-20 text-center">
    <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-100 rounded-full mb-6">
        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-4xl font-extrabold text-slate-900 mb-4">Pendaftaran Diterima!</h1>
    <p class="text-slate-500 text-lg mb-8">Pengajuan kepanitiaan Anda sedang diverifikasi oleh tim AmikomEventHub. Kami akan mengirim notifikasi ke email PIC dalam 1-2 hari kerja.</p>

    <div class="bg-white rounded-3xl shadow-xl p-8 text-left space-y-4">
        <h2 class="font-bold text-slate-900 text-xl mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-indigo-600"></i> Yang akan terjadi selanjutnya:
        </h2>
        <div class="flex gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
            <div>
                <h3 class="font-bold text-slate-900">Verifikasi Data</h3>
                <p class="text-slate-500 text-sm">Tim kami akan mengecek keaslian dan kelayakan organisasi Anda.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
            <div>
                <h3 class="font-bold text-slate-900">Email Notifikasi</h3>
                <p class="text-slate-500 text-sm">Anda akan menerima email konfirmasi berisi link login ke dashboard.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
            <div>
                <h3 class="font-bold text-slate-900">Mulai Buat Event</h3>
                <p class="text-slate-500 text-sm">Setelah disetujui, Anda bisa langsung membuat event dan mengundang anggota tim.</p>
            </div>
        </div>
    </div>

    <a href="{{ route('home') }}" class="inline-block mt-8 px-6 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-300">
        ← Kembali ke Beranda
    </a>
</div>
@endsection
