<!doctype html>
<html>

<head>
    <title>Price Monitoring Dashboard</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #999; }
    </style>
</head>

<body class="min-h-screen bg-gray-100 font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-4 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <img src="{{ asset('assets/logo.png') }}" width="50" height="50" class="rounded-full border border-white/50 shadow-md" />
                <div>
                    <h1 class="text-xl font-bold leading-tight">DATA PANGAN</h1>
                    <p class="text-[10px] text-gray-300 uppercase tracking-widest">SATUAN INTELIJEN KORBRIMOB POLRI</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-full transition-colors">
                Kembali ke Menu Utama
            </a>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN (Visualizations) -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200" x-data="{ activeTab: 'map' }">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Disparitas Harga Variant</h2>
                        
                        <form method="GET" action="{{ route('price-monitoring') }}" class="flex flex-wrap gap-2 items-center">
                            <input type="hidden" name="date1" value="{{ $date1 }}">
                            <input type="hidden" name="date2" value="{{ $date2 }}">
                            <input type="hidden" name="filter_type" value="{{ $filterType }}">
                            <input type="hidden" name="filter_city" value="{{ $filterCity }}">

                            <select name="commodity" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-teal-500 focus:border-teal-500 outline-none">
                                @foreach($commodities as $comm)
                                    <option value="{{ $comm }}" {{ $selectedCommodity == $comm ? 'selected' : '' }}>{{ $comm }}</option>
                                @endforeach
                            </select>
                            
                            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-teal-500 focus:border-teal-500 outline-none">
                        </form>
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 mb-4 bg-gray-50/50 rounded-t-lg">
                        <button @click="activeTab = 'map'" :class="activeTab === 'map' ? 'border-teal-600 text-teal-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-center border-b-2 font-bold text-xs uppercase tracking-widest transition-all">
                            PETA DISPARITAS HARGA
                        </button>
                        <button @click="activeTab = 'chart'" :class="activeTab === 'chart' ? 'border-teal-600 text-teal-700 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-center border-b-2 font-bold text-xs uppercase tracking-widest transition-all">
                            GRAFIK DISPARITAS HARGA
                        </button>
                    </div>

                    <!-- Map View -->
                    <div x-show="activeTab === 'map'" class="relative h-[500px] w-full bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                        <div id="price-disparity-map" 
                             data-commodity="{{ $selectedCommodity }}" 
                             data-date="{{ $date }}"
                             data-average="{{ $nationalAvg }}"
                             class="h-full w-full"></div>
                        
                        <!-- Legend Overlay -->
                        <div class="absolute bottom-4 left-4 bg-white/95 p-3 rounded-lg shadow-md backdrop-blur-sm z-[400] text-[10px] border border-gray-100">
                            <p class="font-bold mb-2 text-gray-800 border-b pb-1 uppercase tracking-tighter">Indikator Harga</p>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#7f1d1d]"></span> <span>Sangat Tinggi (>20%)</span></div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span> <span>Tinggi (>5%)</span></div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#f3f4f6] border border-gray-200"></span> <span>Normal (±5%)</span></div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#22c55e]"></span> <span>Rendah (<-5%)</span></div>
                                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#14532d]"></span> <span>Sangat Rendah (<-20%)</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart View (Diverging Bar) -->
                    <div x-show="activeTab === 'chart'" class="h-[500px] w-full pt-4">
                        <canvas id="disparityBarChart"></canvas>
                    </div>
                </div>

                <!-- Footer Line Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-md font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-teal-500 rounded-full"></span>
                        Series Perkembangan Harga Nasional ({{ Carbon\Carbon::parse($date)->format('F Y') }})
                    </h3>
                    <div class="h-[300px]">
                        <canvas id="trendLineChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN (Sidebar Table) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-fit lg:sticky lg:top-6">
                <div class="p-5 border-b border-gray-100 bg-gray-50/30 rounded-t-xl">
                    <h2 class="text-md font-bold text-gray-800 mb-4 flex items-center gap-2 uppercase tracking-wide">
                        <span class="text-orange-600">📋</span>
                        BAHAN POKOK
                    </h2>
                    
                    <form method="GET" action="{{ route('price-monitoring') }}" id="sidebarForm">
                        <input type="hidden" name="commodity" value="{{ $selectedCommodity }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        
                        <div class="flex gap-1 mb-4 bg-gray-200/50 p-1 rounded-lg">
                            <button type="button" onclick="document.getElementById('filter_type').value='nasional'; document.getElementById('sidebarForm').submit();" class="flex-1 py-1.5 text-xs rounded-md font-bold transition-all {{ $filterType == 'nasional' ? 'bg-white text-teal-700 shadow-sm' : 'text-gray-500' }}">
                                Nasional
                            </button>
                            <button type="button" onclick="document.getElementById('filter_type').value='city'; document.getElementById('sidebarForm').submit();" class="flex-1 py-1.5 text-xs rounded-md font-bold transition-all {{ $filterType == 'city' ? 'bg-white text-teal-700 shadow-sm' : 'text-gray-500' }}">
                                City
                            </button>
                            <input type="hidden" name="filter_type" id="filter_type" value="{{ $filterType }}">
                        </div>

                        @if($filterType == 'city')
                        <div class="mb-4">
                            <select name="filter_city" onchange="this.form.submit()" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs font-medium outline-none focus:ring-1 focus:ring-teal-500">
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ $filterCity == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Tanggal 1</label>
                                <input type="date" name="date1" value="{{ $date1 }}" onchange="this.form.submit()" class="w-full bg-white border border-gray-200 rounded-lg px-2 py-1.5 text-[10px] font-medium focus:ring-1 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Tanggal 2</label>
                                <input type="date" name="date2" value="{{ $date2 }}" onchange="this.form.submit()" class="w-full bg-white border border-gray-200 rounded-lg px-2 py-1.5 text-[10px] font-medium focus:ring-1 focus:ring-teal-500">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-y-auto max-h-[600px] custom-scrollbar p-2">
                    <table class="w-full text-[11px]">
                        <thead class="bg-gray-50 text-gray-500 sticky top-0 z-10">
                            <tr>
                                <th class="text-left py-2 px-2 font-bold uppercase tracking-tighter">Komoditas</th>
                                <th class="text-right py-2 px-1 font-bold">{{ Carbon\Carbon::parse($date1)->format('d/m') }}</th>
                                <th class="text-right py-2 px-1 font-bold">{{ Carbon\Carbon::parse($date2)->format('d/m') }}</th>
                                <th class="text-center py-2 px-2 font-bold">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tableData as $row)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="py-2.5 px-2">
                                    <div class="font-bold text-gray-700">{{ $row['name'] }}</div>
                                    <div class="text-[9px] text-gray-400 uppercase tracking-tighter">Per Kg</div>
                                </td>
                                <td class="py-2.5 px-1 text-right font-mono text-gray-500">{{ number_format($row['price1'], 0, ',', '.') }}</td>
                                <td class="py-2.5 px-1 text-right font-mono text-gray-800 font-bold">{{ number_format($row['price2'], 0, ',', '.') }}</td>
                                <td class="py-2.5 px-2 text-center">
                                    @if($row['change'] > 0)
                                        <span class="text-red-600 font-black flex items-center justify-center">
                                            <svg class="w-2 h-2 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                                            {{ number_format(abs($row['change_pct']), 1) }}
                                        </span>
                                    @elseif($row['change'] < 0)
                                        <span class="text-green-600 font-black flex items-center justify-center">
                                            <svg class="w-2 h-2 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            {{ number_format(abs($row['change_pct']), 1) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 font-bold text-[9px]">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data from Blade
            const disparityData = @json($disparityData);
            const trendData = @json($trendData);

            // --- Disparity Bar Chart ---
            const barCtx = document.getElementById('disparityBarChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: disparityData.map(d => d.province),
                    datasets: [{
                        label: 'Selisih (Rp)',
                        data: disparityData.map(d => d.deviation),
                        backgroundColor: disparityData.map(d => d.deviation >= 0 ? 'rgba(239, 68, 68, 0.8)' : 'rgba(34, 197, 94, 0.8)'),
                        borderColor: disparityData.map(d => d.deviation >= 0 ? '#ef4444' : '#22c55e'),
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => 'Selisih: Rp ' + ctx.raw.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: '#f1f1f1' }, ticks: { font: { size: 10 } } },
                        y: { grid: { display: false }, ticks: { font: { size: 9 }, autoSkip: false } }
                    }
                }
            });

            // --- Trend Line Chart ---
            const lineCtx = document.getElementById('trendLineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.date),
                    datasets: [{
                        label: 'Harga Rata-rata',
                        data: trendData.map(d => d.price),
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.05)',
                        borderWidth: 2,
                        pointRadius: 2,
                        pointBackgroundColor: '#0d9488',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: false, 
                            grid: { color: '#f1f1f1' },
                            ticks: { 
                                font: { size: 10 },
                                callback: (v) => 'Rp ' + v.toLocaleString('id-ID')
                            } 
                        },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</body>
</html>
