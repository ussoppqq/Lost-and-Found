<div>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900">Statistics Dashboard</h1>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $startDate }} - {{ $endDate }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Filters Card -->
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Period Type</label>
                            <select wire:model.live="periodType" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                            <input 
                                type="date" 
                                wire:model.live="selectedDate"
                                max="{{ $maxDate }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            >
                        </div>
                        <div class="flex items-end">
                            <button 
                                wire:click="downloadPdf"
                                class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 flex items-center justify-center gap-2 shadow-sm"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Reports Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Reports</p>
                            <h3 class="text-3xl font-bold">{{ $totalReports }}</h3>
                            <div class="mt-2 flex gap-3 text-xs">
                                <span class="bg-white/20 px-2 py-1 rounded">Lost: {{ $lostReports }}</span>
                                <span class="bg-white/20 px-2 py-1 rounded">Found: {{ $foundReports }}</span>
                            </div>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Items Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Items</p>
                            <h3 class="text-3xl font-bold">{{ $totalItems }}</h3>
                            <div class="mt-2 flex gap-3 text-xs">
                                <span class="bg-white/20 px-2 py-1 rounded">Stored: {{ $storedItems }}</span>
                                <span class="bg-white/20 px-2 py-1 rounded">Claimed: {{ $claimedItems }}</span>
                            </div>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Claims Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Claims</p>
                            <h3 class="text-3xl font-bold">{{ $totalClaims }}</h3>
                            <div class="mt-2 flex gap-3 text-xs">
                                <span class="bg-white/20 px-2 py-1 rounded">Pending: {{ $pendingClaims }}</span>
                                <span class="bg-white/20 px-2 py-1 rounded">Approved: {{ $approvedClaims }}</span>
                            </div>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Matched Reports Card -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium mb-1">Matched Reports</p>
                            <h3 class="text-3xl font-bold">{{ $matchedReports }}</h3>
                            <div class="mt-2 flex gap-3 text-xs">
                                <span class="bg-white/20 px-2 py-1 rounded">Open: {{ $openReports }}</span>
                                <span class="bg-white/20 px-2 py-1 rounded">Closed: {{ $closedReports }}</span>
                            </div>
                        </div>
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <!-- Report Types Donut Chart -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Report Types Distribution</h3>
                    </div>
                    <div class="p-6">
                        <div id="reportTypeChart" wire:ignore style="min-height: 300px;"></div>
                    </div>
                </div>

                <!-- Item Status Pie Chart -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Item Status Overview</h3>
                    </div>
                    <div class="p-6">
                        <div id="itemStatusChart" wire:ignore style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Trend Chart -->
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daily Report Trends</h3>
                    <p class="text-sm text-gray-500 mt-1">Reports over time by type</p>
                </div>
                <div class="p-6">
                    <div id="trendChart" wire:ignore style="min-height: 350px;"></div>
                </div>
            </div>

            <!-- Bottom Row Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Claim Status Bar Chart -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Claim Status Breakdown</h3>
                    </div>
                    <div class="p-6">
                        <div id="claimStatusChart" wire:ignore style="min-height: 300px;"></div>
                    </div>
                </div>

                <!-- Top Categories Chart -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top 10 Categories</h3>
                    </div>
                    <div class="p-6">
                        <div id="categoryChart" wire:ignore style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ApexCharts CDN -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts" onerror="console.error('Failed to load ApexCharts from CDN')"></script>

    <script>
        let reportTypeChart, itemStatusChart, trendChart, claimChart, categoryChart;

        function destroyCharts() {
            if (reportTypeChart) reportTypeChart.destroy();
            if (itemStatusChart) itemStatusChart.destroy();
            if (trendChart) trendChart.destroy();
            if (claimChart) claimChart.destroy();
            if (categoryChart) categoryChart.destroy();
        }

        function initCharts() {
            console.log('Initializing charts...');
            destroyCharts();

            // Check if ApexCharts is loaded
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts is not loaded!');
                return;
            }

            // Report Type Donut Chart
            const reportTypeData = @json($reportChartData);
            console.log('Report type data:', reportTypeData);
            
            const reportTypeElement = document.querySelector("#reportTypeChart");
            if (!reportTypeElement) {
                console.error('Report type chart element not found!');
                return;
            }
            
            reportTypeChart = new ApexCharts(reportTypeElement, {
                series: [reportTypeData.lost, reportTypeData.found],
                chart: {
                    type: 'donut',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                },
                labels: ['Lost Reports', 'Found Reports'],
                colors: ['#EF4444', '#10B981'],
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' reports';
                        }
                    }
                }
            });
            
            try {
                reportTypeChart.render();
                console.log('Report type chart rendered successfully');
            } catch (error) {
                console.error('Error rendering report type chart:', error);
            }

            // Item Status Pie Chart
            const itemStatusData = @json($itemStatusChartData);
            const itemLabels = Object.keys(itemStatusData);
            const itemValues = Object.values(itemStatusData);
            itemStatusChart = new ApexCharts(document.querySelector("#itemStatusChart"), {
                series: itemValues,
                chart: {
                    type: 'pie',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                },
                labels: itemLabels,
                colors: ['#3B82F6', '#10B981', '#8B5CF6', '#EF4444', '#F59E0B'],
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' items';
                        }
                    }
                }
            });
            itemStatusChart.render();

            // Trend Line Chart
            const trendData = @json($trendChartData);
            const trendDates = trendData.map(d => d.date);
            const trendLost = trendData.map(d => d.lost);
            const trendFound = trendData.map(d => d.found);
            const trendTotal = trendData.map(d => d.total);

            trendChart = new ApexCharts(document.querySelector("#trendChart"), {
                series: [
                    {
                        name: 'Total Reports',
                        data: trendTotal
                    },
                    {
                        name: 'Lost Reports',
                        data: trendLost
                    },
                    {
                        name: 'Found Reports',
                        data: trendFound
                    }
                ],
                chart: {
                    type: 'line',
                    height: 350,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: true
                    }
                },
                colors: ['#3B82F6', '#EF4444', '#10B981'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: trendDates,
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Reports'
                    }
                },
                legend: {
                    position: 'top',
                    fontSize: '14px',
                },
                grid: {
                    borderColor: '#f1f1f1',
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                }
            });
            trendChart.render();

            // Claim Status Bar Chart
            const claimData = @json($claimStatusChartData);
            claimChart = new ApexCharts(document.querySelector("#claimStatusChart"), {
                series: [{
                    name: 'Claims',
                    data: [claimData.pending, claimData.approved, claimData.rejected, claimData.released]
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 5,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                colors: ['#3B82F6'],
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                xaxis: {
                    categories: ['Pending', 'Approved', 'Rejected', 'Released'],
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Claims'
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' claims';
                        }
                    }
                }
            });
            claimChart.render();

            // Top Categories Horizontal Bar Chart
            const categoryData = @json($categoryChartData);
            const categories = Object.keys(categoryData);
            const categoryValues = Object.values(categoryData);
            
            categoryChart = new ApexCharts(document.querySelector("#categoryChart"), {
                series: [{
                    name: 'Items',
                    data: categoryValues
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    fontFamily: 'Inter, sans-serif',
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                colors: ['#8B5CF6'],
                dataLabels: {
                    enabled: true,
                    offsetX: 30,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' items';
                        }
                    }
                }
            });
            categoryChart.render();
        }

        document.addEventListener('livewire:initialized', function() {
            // Add a small delay to ensure DOM is ready
            setTimeout(initCharts, 100);
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('statisticsUpdated', () => {
                console.log('Statistics updated event received');
                initCharts();
            });
        });
    </script>
    @endpush

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    @endpush
</div>