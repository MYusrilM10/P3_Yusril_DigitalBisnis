@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('events.show', $event) }}" class="text-blue-500 hover:text-blue-600 mb-4 inline-block">← Kembali ke acara</a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Berikan Rating & Review</h1>
            <h2 class="text-xl text-gray-600">{{ $event->title }}</h2>
        </div>

        <!-- Event Info Card -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-6 flex gap-4">
            @if($event->poster_path)
                <img src="{{ asset('storage/' . $event->poster_path) }}" alt="{{ $event->title }}" class="w-24 h-24 object-cover rounded">
            @else
                <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                </div>
            @endif
            <div class="flex-1">
                <p class="text-gray-600"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d M Y H:i') }}</p>
                <p class="text-gray-600"><strong>Lokasi:</strong> {{ $event->location }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            @include('components.review-form', ['actionUrl' => route('reviews.store', $event)])
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong><i class="fa-solid fa-lightbulb text-yellow-500"></i> Tips:</strong> Tuliskan pengalaman jujur Anda. Review yang terverifikasi dari pembeli akan membantu calon peserta membuat keputusan yang tepat.
            </p>
        </div>
    </div>
</div>
@endsection
