<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                AH
            </div>
            <span class="text-xl font-bold text-white tracking-tight">
                AmikomEventHub
            </span>
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

            <div x-data="{ open: false }" class="mt-6">
                <button @click="open = !open"
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

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">

        <!-- Konten -->
        @yield('content')

    </main>

</body>

</html>