<!doctype html>
<html>
<head>
    <title>DASBOR HARGA PANGAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">
    <!-- header with gradient -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-8 shadow-lg">
        <div class="container mx-auto px-4">
            <!-- logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('assets/logo.png') }}" width="120" height="120"
                    class="rounded-full border-4 border-white shadow-xl" />
            </div>

            @if(auth()->user()->leveluser == 'Dansat')
            <!-- title -->
            <h1 class="text-4xl font-bold text-center mb-2">DATA PANGAN</h1>
            <p class="text-xl text-center text-gray-200 mb-6">
                SATUAN INTELIJEN KORBRIMOB POLRI
            </p>

            <!-- Tab Navigation -->
            <div class="mt-6">
                <div class="flex justify-center border-b border-gray-400">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 font-semibold focus:outline-none border-b-2 border-white text-white">
                        Data Pangan
                    </a>
                    <a href="{{ route('kurs.dollar') }}"
                        class="px-4 py-2 font-semibold focus:outline-none text-gray-300 hover:text-white">
                        Kurs Dollar
                    </a>
                    <a href="{{ route('harga.emas') }}"
                        class="px-4 py-2 font-semibold focus:outline-none text-gray-300 hover:text-white">
                        Harga Emas
                    </a>
                </div>
            </div>
            @endif
        </div>
    </header>

    <!-- main container -->
    <div class="container mx-auto px-4 py-8">
        <!-- date filter form (for table) -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                🔍 Filter Data Tabel
            </h3>
            <form method="GET" action="{{ route('dashboard') }}">
                <!-- Hidden input to preserve main province filter -->
                @if(request()->filled('provinsi'))
                    <input type="hidden" name="provinsi" value="{{ request()->input('provinsi') }}">
                @endif
                <!-- Hidden inputs to preserve main table filters -->
                @if(request()->filled('komoditas'))
                    <input type="hidden" name="komoditas" value="{{ request()->input('komoditas') }}">
                @endif
                <input type="hidden" name="tanggal_awal" value="{{ $tanggal_awal }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir }}">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="komoditas" class="block text-sm font-medium text-gray-700">Bahan Pangan</label>
                        <select name="komoditas" id="komoditas" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Semua</option>
                            @foreach($commodities as $item)
                                <option value="{{ $item }}" {{ old('komoditas', request()->input('komoditas')) == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            <option value="">Semua</option>
                            @foreach($provinces as $item)
                                <option value="{{ $item }}" {{ old('provinsi', request()->input('provinsi')) == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggal_awal }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggal_akhir }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Filter</button>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Reset</a>
                    <a href="{{ route('dashboard.export-excel') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Ekspor 1 Bulan (Excel)</a>
                </div>
            </form>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-xl p-6 mt-8 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Grafik Harga Bahan Pangan</h3>
            <div x-data="{ chartData: {{ json_encode($barChartData) }} }" x-init="
                let ctx = $refs.priceChart.getContext('2d');
                let chart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false, // Hide legend for single dataset
                            },
                            title: {
                                display: true,
                                text: 'Harga Terkini Bahan Pangan'
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Bahan Pangan'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Harga (Rp)'
                                },
                                ticks: {
                                    callback: function(value, index, values) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });

                $watch('chartData', value => {
                    chart.data = value;
                    chart.update();
                });
            ">
                <canvas x-ref="priceChart" class="h-96"></canvas>
            </div>
        </div>


        <!-- navigation menu -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">
                Menu Navigasi
            </h3>
            <ul class="space-y-3">
                @if(auth()->user()->role == 'admin')
                <li>
                    <a href="{{ route('admin.bahan-pangan.index') }}"
                        class="block px-4 py-3 bg-gray-100 hover:bg-red-800 hover:text-white rounded-lg font-semibold transition-all duration-300 border border-gray-300 hover:border-red-800">
                        📦 Manajemen Bahan Pangan
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="block px-4 py-3 bg-gray-100 hover:bg-red-800 hover:text-white rounded-lg font-semibold transition-all duration-300 border border-gray-300 hover:border-red-800">
                        👥 Manajemen Pengguna
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->leveluser, ['anggota', 'Dansat']))
                <li>
                    <a href="{{ route('data.transaksi') }}"
                        class="block px-4 py-3 bg-gray-100 hover:bg-red-800 hover:text-white rounded-lg font-semibold transition-all duration-300 border border-gray-300 hover:border-red-800">
                        📊 Transaksi Pembayaran
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('data.history-pembayaran') }}"
                        class="block px-4 py-3 bg-gray-100 hover:bg-red-800 hover:text-white rounded-lg font-semibold transition-all duration-300 border border-gray-300 hover:border-red-800">
                        📋 Riwayat Pembayaran
                    </a>
                </li>
            </ul>
        </div>

        <!-- logout button -->
        <div class="mt-8 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="bg-red-800 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl uppercase tracking-wide">
                    🚪 Keluar
                </button>
            </form>
        </div>

        <!-- footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>
                &copy; {{ date('Y') }} Jl. Anyelir No.23, Pasir Gn. Sel., Kec. Cimanggis, Kota Depok, Jawa Barat 16451
            </p>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</html>
