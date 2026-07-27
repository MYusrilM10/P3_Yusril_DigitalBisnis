@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-3xl mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900 mb-3">Daftar Jadi Penyelenggara</h1>
        <p class="text-slate-500 text-lg">Buat akun kepanitiaan dan kelola event organisasi Anda di AmikomEventHub</p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-2xl">
        <p class="text-red-700 font-bold mb-2">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside text-red-600 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('organization.register.submit') }}" method="POST" class="bg-white rounded-3xl shadow-xl p-8 md:p-10 space-y-6">
        @csrf

        <div class="border-b border-slate-200 pb-4">
            <h2 class="text-xl font-bold text-slate-900">Informasi Organisasi</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Organisasi *</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="HIMA Sistem Informasi" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jenis *</label>
                <select name="type" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
                    <option value="hima" {{ old('type') == 'hima' ? 'selected' : '' }}>HIMA</option>
                    <option value="bem" {{ old('type') == 'bem' ? 'selected' : '' }}>BEM</option>
                    <option value="kepanitiaan" {{ old('type') == 'kepanitiaan' ? 'selected' : '' }}>Kepanitiaan</option>
                    <option value="ukm" {{ old('type') == 'ukm' ? 'selected' : '' }}>UKM</option>
                    <option value="external" {{ old('type') == 'external' ? 'selected' : '' }}>External</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi *</label>
            <textarea name="description" required rows="3" placeholder="Ceritakan tentang organisasi Anda..." class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Organisasi *</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="hima.si@amikom.ac.id" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon *</label>
                <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="081234567890" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat (opsional)</label>
            <input type="text" name="address" value="{{ old('address') }}" placeholder="Universitas Amikom Yogyakarta" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
        </div>

        <div class="border-b border-slate-200 pb-4 pt-4">
            <h2 class="text-xl font-bold text-slate-900">Data Penanggung Jawab (PIC)</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama PIC *</label>
                <input type="text" name="pic_name" required value="{{ old('pic_name') }}" placeholder="Nama lengkap" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email PIC *</label>
                <input type="email" name="pic_email" required value="{{ old('pic_email') }}" placeholder="email@amikom.ac.id" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Password *</label>
            <input type="password" name="pic_password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password *</label>
            <input type="password" name="pic_password_confirmation" required minlength="6" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-600 focus:outline-none">
        </div>

        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
            <p class="text-sm text-blue-700">
                <strong>Catatan:</strong> Pendaftaran Anda akan diverifikasi oleh tim AmikomEventHub dalam 1-2 hari kerja. Setelah disetujui, PIC akan otomatis menjadi <strong>Owner</strong> organisasi.
            </p>
        </div>

        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            Daftar Sekarang
        </button>

        <div class="text-center pt-2">
            <p class="text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:text-indigo-700">
                    Login di sini
                </a>
            </p>
        </div>
    </form>
</div>
@endsection
