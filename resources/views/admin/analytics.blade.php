@extends('layouts.admin')
@section('content')
<div class="p-8 bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Analytics Global</h2>
            <p class="text-gray-500 text-sm mt-1">Ringkasan performa platform seluruh kepanitiaan.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Total Org</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrgs }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Total Event</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalEvents }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pendingPayouts }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 col-span-2">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Total Revenue (Net)</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 col-span-2">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Total Komisi Platform</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalCommission, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 5 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Top 5 Org by Revenue</th>
                            <th class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topOrgs as $org)
                        <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-colors duration-200 group">
                            <td class="px-6 py-4 font-semibold text-gray-800 group-hover:text-indigo-600 transition">{{ $org->name }}</td>
                            <td class="px-6 py-4 text-right text-gray-800 font-semibold">Rp {{ number_format($org->totalRevenue(), 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="px-6 py-12 text-center text-gray-500 font-medium">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Pertumbuhan Pengguna (30 Hari)</h3>
                </div>
                <div id="userGrowthChart" style="min-height: 400px;"></div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Pertumbuhan Event (30 Hari)</h3>
                </div>
                <div id="eventGrowthChart" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('load', function() {
        renderAnalyticsCharts();
    });

    function renderAnalyticsCharts() {
        const labels = @json($growthLabels ?? []);
        const userGrowthData = @json($userGrowth ?? []);
        const eventGrowthData = @json($eventGrowth ?? []);

        console.log('Analytics Chart Data:', { labels, userGrowthData, eventGrowthData });

        if (!labels || labels.length === 0 || !userGrowthData || !eventGrowthData) {
            console.error('Analytics chart data is missing or empty');
            return;
        }

        if (typeof ApexCharts === 'undefined') {
            console.error('ApexCharts library not loaded');
            return;
        }

        const userChartEl = document.getElementById('userGrowthChart');
        const eventChartEl = document.getElementById('eventGrowthChart');

        if (!userChartEl || !eventChartEl) {
            console.error('Chart containers not found');
            return;
        }

        try {
            // User Growth Chart
            const userChartOptions = {
                series: [{
                    name: 'User Baru',
                    data: userGrowthData,
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    toolbar: {
                        show: true,
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top',
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return Math.round(val);
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#4f46e5'],
                        fontWeight: 600,
                    },
                },
                colors: ['#4f46e5'],
                xaxis: {
                    categories: labels,
                    position: 'bottom',
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    crosshairs: {
                        fill: {
                            type: 'gradient',
                            gradient: {
                                colorFrom: '#D8E3F0',
                                colorTo: '#BED1E6',
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                        },
                        formatter: function (val) {
                            return new Date(val).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                        },
                    }
                },
                yaxis: {
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: true,
                        style: {
                            fontSize: '11px',
                        }
                    },
                },
                tooltip: {
                    theme: 'light',
                    x: {
                        formatter: function (val, opts) {
                            return new Date(labels[opts.dataPointIndex]).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                        },
                    },
                    y: {
                        formatter: function (val) {
                            return Math.round(val) + ' user';
                        }
                    }
                },
                grid: {
                    borderColor: '#e2e8f0',
                    strokeDashArray: 5,
                }
            };

            // Event Growth Chart
            const eventChartOptions = {
                series: [{
                    name: 'Event Baru',
                    data: eventGrowthData,
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    fontFamily: "'Plus Jakarta Sans', sans-serif",
                    toolbar: {
                        show: true,
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top',
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return Math.round(val);
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#f97316'],
                        fontWeight: 600,
                    },
                },
                colors: ['#f97316'],
                xaxis: {
                    categories: labels,
                    position: 'bottom',
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    crosshairs: {
                        fill: {
                            type: 'gradient',
                            gradient: {
                                colorFrom: '#FFF1E6',
                                colorTo: '#FFE6CC',
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                            },
                        },
                    },
                    tooltip: {
                        enabled: true,
                    },
                    labels: {
                        style: {
                            fontSize: '11px',
                        },
                        formatter: function (val) {
                            return new Date(val).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                        },
                    }
                },
                yaxis: {
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: true,
                        style: {
                            fontSize: '11px',
                        }
                    },
                },
                tooltip: {
                    theme: 'light',
                    x: {
                        formatter: function (val, opts) {
                            return new Date(labels[opts.dataPointIndex]).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                        },
                    },
                    y: {
                        formatter: function (val) {
                            return Math.round(val) + ' event';
                        }
                    }
                },
                grid: {
                    borderColor: '#e2e8f0',
                    strokeDashArray: 5,
                }
            };

            console.log('Creating user chart with options:', userChartOptions);
            const userChart = new ApexCharts(userChartEl, userChartOptions);
            userChart.render().then(() => {
                console.log('User chart rendered successfully');
            }).catch(err => {
                console.error('User chart render error:', err);
            });

            console.log('Creating event chart with options:', eventChartOptions);
            const eventChart = new ApexCharts(eventChartEl, eventChartOptions);
            eventChart.render().then(() => {
                console.log('Event chart rendered successfully');
            }).catch(err => {
                console.error('Event chart render error:', err);
            });
        } catch (error) {
            console.error('Error rendering analytics charts:', error);
        }
    }
</script>
@endpush
@endsection
