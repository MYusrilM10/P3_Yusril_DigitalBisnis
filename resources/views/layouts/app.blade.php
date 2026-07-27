<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="icon" href="/assets/logo.ico">
    <link rel="apple-touch-icon" href="/assets/logo.webp">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="glass sticky top-8 z-40 mx-4 mt-4 px-4 md:px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center gap-3">
        <a href="{{ route('home') }}" class="flex items-center gap-2 min-w-0">
            <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0">
                <img src="/assets/logo.webp" alt="AmikomEventHub" class="w-full h-full object-cover">
            </div>
            <span class="text-lg md:text-xl font-bold tracking-tight truncate">AmikomEventHub</span>
        </a>
        <div class="flex items-center gap-3 md:gap-8 font-medium shrink-0">
            <a href="{{ route('home') }}" class="text-indigo-600 hidden sm:inline">Jelajahi</a>
            @auth
                @if(auth()->user()->isSuperadmin())
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-sm whitespace-nowrap">Admin</a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- KONTEN DINAMIS -->
    @yield('content')

    @include('partials.pwa-install-banner')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

</body>

</html>
