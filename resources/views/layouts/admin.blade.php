<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="icon" href="/assets/logo.ico">
    <link rel="apple-touch-icon" href="/assets/logo.webp">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

    <!-- Sidebar backdrop (mobile only) -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 fixed md:sticky top-0 h-screen z-50 transition-transform duration-200 md:translate-x-0">

        <!-- Logo -->
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0">
                    <img src="/assets/logo.webp" alt="AmikomEventHub" class="w-full h-full object-cover">
                </div>
                <span class="text-xl font-bold text-white tracking-tight truncate">
                    AmikomEventHub
                </span>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-indigo-200 hover:text-white shrink-0">
                <i class="fa-solid fa-xmark w-5 h-5"></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 space-y-2">

            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">
                Main Menu
            </p>

            <!-- Dashboard -->
            <a href="/admin"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">

                <i class="fa-solid fa-gauge w-5 h-5"></i>

                Dashboard
            </a>

            <!-- Event -->
            <a href="/admin/events"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">

                <i class="fa-solid fa-calendar-days w-5 h-5"></i>

                Kelola Event
            </a>

            <!-- Transaksi -->
            <a href="/admin/transactions"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">

                <i class="fa-solid fa-receipt w-5 h-5"></i>

                Laporan Transaksi
            </a>

            <!-- Kategori -->
            <a href="/admin/categories"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">

                <i class="fa-solid fa-tag w-5 h-5"></i>

                Kategori
            </a>

            <!-- PARTNER BARU -->
            <a href="/admin/partners"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">

                <i class="fa-solid fa-handshake w-5 h-5"></i>

                Partner
            </a>

            <div x-data="{ 
                open: @if(Route::is('admin.tenants.*', 'admin.payouts.*', 'admin.analytics', 'admin.komisi')) true @else false @endif 
            }" class="mt-6">
                <button @click="open = !open; localStorage.setItem('sidebarMultiTenantOpen', open)"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-transparent hover:bg-indigo-800 rounded-xl font-bold text-left">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-building w-5 h-5"></i>
                        <span>Multi-Tenant</span>
                    </div>
                    <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="w-4 h-4 text-indigo-200"></i>
                </button>

                <div x-show="open" x-transition class="mt-2 space-y-1 pl-6">
                    <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-800 rounded-xl font-medium text-indigo-100">
                        <i class="fa-solid fa-building w-4 h-4"></i> Tenant
                    </a>
                    <a href="{{ route('admin.tenants.pending') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-800 rounded-xl font-medium text-indigo-100">
                        <i class="fa-solid fa-clock w-4 h-4"></i> Pending
                    </a>
                    <a href="{{ route('admin.payouts.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-800 rounded-xl font-medium text-indigo-100">
                        <i class="fa-solid fa-money-bill-wave w-4 h-4"></i> Payouts
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-800 rounded-xl font-medium text-indigo-100">
                        <i class="fa-solid fa-chart-line w-4 h-4"></i> Analytics
                    </a>
                    <a href="{{ route('admin.komisi') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-800 rounded-xl font-medium text-indigo-100">
                        <i class="fa-solid fa-percent w-4 h-4"></i> Komisi
                    </a>
                </div>
            </div>

        </nav>

        <!-- Logout -->
        <div class="pt-6 border-t border-indigo-800">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white font-medium text-left">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 h-5"></i>
                    Keluar
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
            <span class="font-bold text-slate-800">Admin Panel</span>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-10 overflow-y-auto min-w-0">

            <!-- Konten -->
            @yield('content')

        </main>

    </div>

    </div>

    @stack('scripts')

    @include('partials.pwa-install-banner')

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

</body>

</html>