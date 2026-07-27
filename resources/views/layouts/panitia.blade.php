<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $org->name }} - Panitia Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .menu-active { background-color: rgb(55 48 163) !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-6 sticky top-0 h-screen overflow-y-auto">

        <!-- Header -->
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                    AH
                </div>
                <span class="text-lg font-bold text-white tracking-tight">AmikomEventHub</span>
            </a>
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

    <!-- Main Content -->
    <main class="flex-1 p-8">
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-2xl text-red-700 font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
