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

        <!-- Chart Filter Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                📈 Filter Data Grafik
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


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="chart_tanggal_awal" class="block text-sm font-medium text-gray-700">Tanggal Mulai (Grafik)</label>
                        <input type="date" name="chart_tanggal_awal" id="chart_tanggal_awal" value="{{ $chart_tanggal_awal }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="chart_tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Selesai (Grafik)</label>
                        <input type="date" name="chart_tanggal_akhir" id="chart_tanggal_akhir" value="{{ $chart_tanggal_akhir }}" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bahan Pangan (Grafik)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
                        @foreach($commodities as $item)
                            <div class="flex items-center">
                                <input type="checkbox" name="chart_komoditas[]" value="{{ $item }}" id="chart_komoditas_{{ Str::slug($item) }}"
                                    class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                    {{ in_array($item, $chart_komoditas_selected) ? 'checked' : '' }}>
                                <label for="chart_komoditas_{{ Str::slug($item) }}" class="ml-2 block text-sm text-gray-900">{{ $item }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Filter Grafik</button>
                    <a href="{{ route('dashboard', ['provinsi' => request()->input('provinsi'), 'komoditas' => request()->input('komoditas'), 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Reset Grafik</a>
                </div>
            </form>
        </div>

        <!-- card table -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
            <table id="hargaTable" class="w-full">
                <thead>
                    <tr class="bg-red-800 text-white">
                        <th class="py-4 px-6 text-xl font-bold border-r border-red-700">
                            Komoditas
                        </th>
                        <th class="py-4 px-6 text-xl font-bold border-r border-red-700">
                            Hari Ini (Rp)
                        </th>
                        <th class="py-4 px-6 text-xl font-bold border-r border-red-700">
                            Kemarin (Rp)
                        </th>
                        <th class="py-4 px-6 text-xl font-bold">
                            Perubahan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data_per_komoditas) > 0)
                        @foreach($data_per_komoditas as $komoditas => $data_komoditas)
                            @php
                                // get first and last price in range
                                $harga_awal = $data_komoditas[0]['harga'];
                                $harga_akhir = $data_komoditas[count($data_komoditas) - 1]['harga'];

                                // calculate price change
                                $perubahan = $harga_akhir - $harga_awal;
                                $persentase = ($harga_awal != 0) ? ($perubahan / $harga_awal) * 100 : 0;

                                // determine color and symbol
                                if ($perubahan > 0) {
                                    $warna = 'text-red-600'; // increase = red
                                    $simbol = '▲';
                                } elseif ($perubahan < 0) {
                                    $warna = 'text-green-600'; // decrease = green
                                    $simbol = '▼';
                                } else {
                                    $warna = 'text-gray-600'; // no change = gray
                                    $simbol = '━';
                                }

                                $teks_perubahan = $simbol . ' Rp ' . number_format(abs($perubahan), 0, ',', '.') .
                                                ' (' . number_format(abs($persentase), 1) . '%)';
                            @endphp
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-6 font-semibold border-r border-gray-200">
                                    {{ $komoditas }}
                                </td>
                                <td class="py-3 px-6 text-center border-r border-gray-200">
                                    Rp {{ number_format($harga_akhir, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-6 text-center border-r border-gray-200">
                                    Rp {{ number_format($harga_awal, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-6 text-center font-semibold {{ $warna }}">
                                    {{ $teks_perubahan }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="border-b border-gray-200">
                            <td colspan="4" class="py-3 px-6 text-center text-gray-500">
                                Tidak ada data yang tersedia untuk rentang tanggal ini
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-xl p-6 mt-8 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Tren Harga Bahan Pangan</h3>
            <div x-data="{ chartData: {{ json_encode($chartData) }} }" x-init="
                let ctx = $refs.priceChart.getContext('2d');
                let chart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Pergerakan Harga Bahan Pangan'
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Tanggal'
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Harga (Rp)'
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
                &copy; {{ date('Y') }} Data Pangan Indonesia - KORBRIMOB POLRI
            </p>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</html>
