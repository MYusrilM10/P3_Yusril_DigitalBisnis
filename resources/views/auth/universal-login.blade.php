@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-6 py-20">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">

            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-black text-slate-900">Login</h1>
                <p class="text-slate-500 text-sm">Masuk ke akun AmikomEventHub Anda</p>
            </div>

            @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-600 text-sm font-medium">• {{ session('error') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           placeholder="email@amikom.ac.id"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center gap-3">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-bold uppercase">atau</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <!-- SSO: Continue with Google -->
            <a href="{{ route('auth.google') }}"
               class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 bg-white border-2 border-slate-200 rounded-xl font-bold text-slate-700 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <!-- Info Otomatis Redirect -->
            <div class="mt-6 p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                <p class="text-xs text-indigo-700 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Setelah login, Anda akan diarahkan otomatis ke dashboard sesuai peran Anda
                    (Admin, Panitia, atau User).
                </p>
            </div>

            <!-- Link Daftar (Lebih Menonjol) -->
            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <p class="text-sm text-slate-600 mb-3">Belum punya akun?</p>
                <a href="{{ route('organization.register') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition w-full justify-center">
                    <i class="fa-solid fa-user-plus"></i>
                    Daftar Akun Baru
                </a>
                <p class="text-xs text-slate-400 mt-2">Pendaftaran untuk penyelenggara event kampus</p>
            </div>
        </div>
    </div>
</div>
@endsection
