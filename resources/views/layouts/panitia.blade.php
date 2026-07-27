<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $org->name }} - Panitia Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="icon" href="/assets/logo.ico">
    <link rel="apple-touch-icon" href="/assets/logo.webp">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .menu-active { background-color: rgb(55 48 163) !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

    <!-- Sidebar backdrop (mobile only) -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-6 fixed md:sticky top-0 h-screen overflow-y-auto z-50 transition-transform duration-200 md:translate-x-0">

        <!-- Header -->
        <div>
            <div class="flex items-center justify-between gap-3 mb-2">
                <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0">
                        <img src="/assets/logo.webp" alt="AmikomEventHub" class="w-full h-full object-cover">
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight truncate">AmikomEventHub</span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-indigo-200 hover:text-white shrink-0">
                    <i class="fa-solid fa-xmark w-5 h-5"></i>
                </button>
            </div>
            <div class="mt-3 p-3 bg-indigo-800 rounded-xl">
                <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest">Panitia</p>
                <p class="text-white font-bold truncate">{{ $org->name }}</p>
                <p class="text-xs text-indigo-300">{{ ucfirst($org->type) }}</p>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 space-y-1">

            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-3 px-2">
                Main
            </p>

            <a href="{{ route('panitia.dashboard', $org->slug) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold {{ request()->routeIs('panitia.dashboard') ? 'menu-active' : '' }}">
                <i class="fa-solid fa-gauge w-5 h-5"></i>
                Dashboard
            </a>

            <a href="{{ route('panitia.events.index', $org->slug) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold {{ request()->routeIs('panitia.events.*') ? 'menu-active' : '' }}">
                <i class="fa-solid fa-calendar-days w-5 h-5"></i>
                Kelola Event
            </a>

            <a href="{{ route('panitia.analytics', $org->slug) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold {{ request()->routeIs('panitia.analytics') ? 'menu-active' : '' }}">
                <i class="fa-solid fa-chart-line w-5 h-5"></i>
                Analytics
            </a>

            <a href="{{ route('panitia.payouts', $org->slug) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold {{ request()->routeIs('panitia.payouts') ? 'menu-active' : '' }}">
                <i class="fa-solid fa-money-bill-wave w-5 h-5"></i>
                Payout
            </a>

            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mt-6 mb-3 px-2">
                Team
            </p>

            <a href="{{ route('panitia.staff', $org->slug) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold {{ request()->routeIs('panitia.staff') ? 'menu-active' : '' }}">
                <i class="fa-solid fa-users w-5 h-5"></i>
                Kelola Staff
            </a>

            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mt-6 mb-3 px-2">
                Public
            </p>

            <a href="{{ route('panitia.show', $org->slug) }}" target="_blank"
               class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-medium text-indigo-200">
                <i class="fa-solid fa-external-link w-5 h-5"></i>
                Lihat Profile Publik
            </a>
        </nav>

        <!-- Logout -->
        <div class="pt-4 border-t border-indigo-800">
            <div class="px-4 py-2 text-xs text-indigo-300">
                <p class="font-bold text-white">{{ auth()->user()->name }}</p>
                <p class="truncate">{{ auth()->user()->email }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white font-medium text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 h-5"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">

        <!-- Mobile Top Bar -->
        <div class="md:hidden sticky top-0 z-30 bg-white border-b border-slate-200 px-4 py-3 flex items-center gap-3">
            <button @click="sidebarOpen = true" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="font-bold text-slate-800 truncate">{{ $org->name }}</span>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8 min-w-0">
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-2xl text-red-700 font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>

    </div>

    </div>

    @include('partials.pwa-install-banner')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

</body>
</html>
