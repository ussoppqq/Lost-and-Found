<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Page Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Statistics Dashboard
                        </h1>
                        <div class="mt-2 flex items-center text-sm text-gray-600">
                            <svg class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-semibold">{{ $formattedStartDate }}</span>
                            <span class="mx-2">—</span>
                            <span class="font-semibold">{{ $formattedEndDate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Filters Card -->
            <div class="bg-white rounded-xl shadow-md mb-8 border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Filters & Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Period Type</label>
                            <select wire:model.live="periodType" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm">
                                <option value="weekly">📅 Weekly</option>
                                <option value="monthly">📆 Monthly</option>
                                <option value="yearly">🗓️ Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                            <input 
                                type="date" 
                                wire:model.live="startDate"
                                max="{{ $maxDate }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input 
                                type="date" 
                                wire:model.live="endDate"
                                max="{{ $maxDate }}"
                                min="{{ $startDate }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                            >
                        </div>
                        <div class="flex items-end">
                            <button 
                                wire:click="downloadPdf"
                                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-150 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Lost Reports Card -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200 border-2 border-red-400">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-red-100 text-xs font-semibold uppercase tracking-wide mb-1">Lost Reports</p>
                            <h3 class="text-4xl font-extrabold mb-2">{{ $lostReports }}</h3>
                            <div class="space-y-1 text-xs">
                                <div class="bg-white/20 px-3 py-1.5 rounded-md inline-block">
                                    Open: {{ $openReports }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Found Reports Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200 border-2 border-green-400">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-green-100 text-xs font-semibold uppercase tracking-wide mb-1">Found Reports</p>
                            <h3 class="text-4xl font-extrabold mb-2">{{ $foundReports }}</h3>
                            <div class="space-y-1 text-xs">
                                <div class="bg-white/20 px-3 py-1.5 rounded-md inline-block">
                                    Matched: {{ $matchedReports }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Items Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200 border-2 border-blue-400">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-blue-100 text-xs font-semibold uppercase tracking-wide mb-1">Total Items</p>
                            <h3 class="text-4xl font-extrabold mb-2">{{ $totalItems }}</h3>
                            <div class="space-y-1 text-xs">
                                <div class="bg-white/20 px-3 py-1.5 rounded-md inline-block">
                                    Stored: {{ $storedItems }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Claims Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200 border-2 border-purple-400">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-purple-100 text-xs font-semibold uppercase tracking-wide mb-1">Total Claims</p>
                            <h3 class="text-4xl font-extrabold mb-2">{{ $totalClaims }}</h3>
                            <div class="space-y-1 text-xs">
                                <div class="bg-white/20 px-3 py-1.5 rounded-md inline-block">
                                    Pending: {{ $pendingClaims }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/20 p-4 rounded-full">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Report Types Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Report Types Distribution</h3>
                        <p class="text-sm text-gray-500 mt-1">Lost vs Found reports</p>
                    </div>
                    <div class="p-6">
                        <div id="reportTypeChart" wire:ignore style="min-height: 320px;"></div>
                    </div>
                </div>

                <!-- Item Status Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Item Status Overview</h3>
                        <p class="text-sm text-gray-500 mt-1">Distribution by status</p>
                    </div>
                    <div class="p-6">
                        <div id="itemStatusChart" wire:ignore style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>

            <!-- Trend Chart Full Width -->
            <div class="bg-white rounded-xl shadow-md mb-6 border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Daily Report Trends</h3>
                    <p class="text-sm text-gray-500 mt-1">Reports over time by type</p>
                </div>
                <div class="p-6">
                    <div id="trendChart" wire:ignore style="min-height: 380px;"></div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Categories Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Top 10 Categories</h3>
                        <p class="text-sm text-gray-500 mt-1">Most reported categories</p>
                    </div>
                    <div class="p-6">
                        <div id="categoryChart" wire:ignore style="min-height: 320px;"></div>
                    </div>
                </div>

                <!-- Claim Status Chart -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Claim Status Breakdown</h3>
                        <p class="text-sm text-gray-500 mt-1">Claims by status</p>
                    </div>
                    <div class="p-6">
                        <div id="claimStatusChart" wire:ignore style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        let charts = {
            reportType: null,
            itemStatus: null,
            trend: null,
            claim: null,
            category: null
        };

        function destroyCharts() {
            Object.values(charts).forEach(chart => {
                if (chart) {
                    chart.destroy();
                }
            });
            charts = {
                reportType: null,
                itemStatus: null,
                trend: null,
                claim: null,
                category: null
            };
        }

        function initCharts(data = null) {
            console.log('Initializing charts...', data);
            
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts not loaded!');
                return;
            }

            // Destroy existing charts first
            destroyCharts();

            // Get data from either parameter or blade variables
            const reportData = data?.reportChartData || @json($reportChartData);
            const itemData = data?.itemStatusChartData || @json($itemStatusChartData);
            const claimData = data?.claimStatusChartData || @json($claimStatusChartData);
            const categoryData = data?.categoryChartData || @json($categoryChartData);
            const trendData = data?.trendChartData || @json($trendChartData);

            // 1. Report Type Donut
            charts.reportType = new ApexCharts(document.querySelector("#reportTypeChart"), {
                series: [reportData.lost || 0, reportData.found || 0],
                chart: { type: 'donut', height: 320, fontFamily: 'Inter, sans-serif' },
                labels: ['Lost Reports', 'Found Reports'],
                colors: ['#EF4444', '#10B981'],
                legend: { position: 'bottom', fontSize: '14px' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: { 
                                    show: true, 
                                    label: 'Total', 
                                    fontSize: '18px', 
                                    fontWeight: 700,
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => val.toFixed(1) + '%',
                    style: { fontSize: '14px', fontWeight: 'bold' }
                }
            });
            charts.reportType.render();

            // 2. Item Status Pie
            const itemLabels = Object.keys(itemData);
            const itemValues = Object.values(itemData);
            
            if (itemValues.length > 0) {
                charts.itemStatus = new ApexCharts(document.querySelector("#itemStatusChart"), {
                    series: itemValues,
                    chart: { type: 'pie', height: 320, fontFamily: 'Inter, sans-serif' },
                    labels: itemLabels,
                    colors: ['#3B82F6', '#10B981', '#8B5CF6', '#EF4444', '#F59E0B'],
                    legend: { position: 'bottom', fontSize: '14px' },
                    dataLabels: {
                        enabled: true,
                        formatter: (val) => val.toFixed(1) + '%',
                        style: { fontSize: '13px', fontWeight: 'bold' }
                    }
                });
                charts.itemStatus.render();
            }

            // 3. Trend Line with Total Reports
            if (trendData.length > 0) {
                charts.trend = new ApexCharts(document.querySelector("#trendChart"), {
                    series: [
                        { name: 'Total Reports', data: trendData.map(d => d.total) },
                        { name: 'Lost Reports', data: trendData.map(d => d.lost) },
                        { name: 'Found Reports', data: trendData.map(d => d.found) }
                    ],
                    chart: { 
                        type: 'line', 
                        height: 380, 
                        fontFamily: 'Inter, sans-serif', 
                        toolbar: { show: true }, 
                        zoom: { enabled: true } 
                    },
                    colors: ['#3B82F6', '#EF4444', '#10B981'],
                    stroke: { curve: 'smooth', width: 3 },
                    xaxis: { categories: trendData.map(d => d.date) },
                    yaxis: { title: { text: 'Number of Reports' } },
                    legend: { position: 'top', fontSize: '14px' },
                    grid: { borderColor: '#f1f1f1' },
                    markers: { size: 4 }
                });
                charts.trend.render();
            }

            // 4. Category Horizontal Bar
            const categories = Object.keys(categoryData);
            const categoryValues = Object.values(categoryData);
            
            if (categoryValues.length > 0) {
                charts.category = new ApexCharts(document.querySelector("#categoryChart"), {
                    series: [{ name: 'Reports', data: categoryValues }],
                    chart: { type: 'bar', height: 320, fontFamily: 'Inter, sans-serif' },
                    plotOptions: { 
                        bar: { 
                            horizontal: true, 
                            borderRadius: 6, 
                            dataLabels: { position: 'top' } 
                        } 
                    },
                    colors: ['#8B5CF6'],
                    dataLabels: { 
                        enabled: true, 
                        offsetX: 30, 
                        style: { fontSize: '12px', fontWeight: 'bold' } 
                    },
                    xaxis: { categories: categories },
                    grid: { borderColor: '#f1f1f1' }
                });
                charts.category.render();
            }

            // 5. Claim Status Bar
            charts.claim = new ApexCharts(document.querySelector("#claimStatusChart"), {
                series: [{
                    name: 'Claims',
                    data: [
                        claimData.pending || 0,
                        claimData.rejected || 0,
                        claimData.released || 0
                    ]
                }],
                chart: { type: 'bar', height: 320, fontFamily: 'Inter, sans-serif' },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '60%',
                        dataLabels: { position: 'top' }
                    }
                },
                colors: ['#3B82F6'],
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: { fontSize: '12px', fontWeight: 'bold' }
                },
                xaxis: { categories: ['Pending', 'Rejected', 'Released'] },
                yaxis: { title: { text: 'Number of Claims' } },
                grid: { borderColor: '#f1f1f1' }
            });
            charts.claim.render();

            console.log('Charts initialized successfully');
        }

        // Initialize charts when page loads
        document.addEventListener('livewire:initialized', () => {
            setTimeout(() => initCharts(), 100);
        });

        // Listen for statistics update event
        document.addEventListener('livewire:init', () => {
            Livewire.on('statisticsUpdated', (event) => {
                console.log('Statistics updated event received', event);
                const data = event[0] || event;
                setTimeout(() => initCharts(data), 100);
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
    @endpush
</div>