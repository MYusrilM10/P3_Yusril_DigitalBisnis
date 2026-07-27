@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md">
        <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black mb-4">Terima Kasih!</h2>
        <p class="text-slate-500 mb-6 leading-relaxed">
            Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil.
            E-Ticket akan dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
        </p>

        <div class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-left">
            <p class="text-xs font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="fa-solid fa-star text-yellow-500"></i>
                Ingin memberi ulasan setelah acara?
            </p>
            <p class="text-xs text-slate-600 leading-relaxed">
                Kami akan mengirimkan tautan khusus ke email Anda
                <strong>{{ $transaction->customer_email }}</strong>
                setelah acara selesai. Anda bisa memberi rating & testimoni langsung dari email tersebut — tanpa perlu login atau membuat akun.
            </p>
        </div>

        <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
