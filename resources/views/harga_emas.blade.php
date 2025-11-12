<!doctype html>
<html>
<head>
    <title>HARGA EMAS - DASBOR HARGA PANGAN</title>
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
            <h1 class="text-4xl font-bold text-center mb-2">HARGA EMAS</h1>
            <p class="text-xl text-center text-gray-200 mb-6">
                SATUAN INTELIJEN KORBRIMOB POLRI
            </p>

            <!-- Tab Navigation -->
            <div class="mt-6">
                <div class="flex justify-center border-b border-gray-400">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 font-semibold focus:outline-none text-gray-300 hover:text-white">
                        Data Pangan
                    </a>
                    <a href="{{ route('kurs.dollar') }}"
                        class="px-4 py-2 font-semibold focus:outline-none text-gray-300 hover:text-white">
                        Kurs Dollar
                    </a>
                    <a href="{{ route('harga.emas') }}"
                        class="px-4 py-2 font-semibold focus:outline-none border-b-2 border-white text-white">
                        Harga Emas
                    </a>
                </div>
            </div>
            @endif
        </div>
    </header>

    <!-- main container -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                Informasi Harga Emas
            </h3>
            @if(isset($error))
                <div class="text-red-500">{{ $error }}</div>
            @elseif(isset($latestPriceFormatted) && isset($latestDate))
                <p class="text-gray-600 mb-2">Tanggal: {{ $latestDate }}</p>
                <p class="text-3xl font-bold text-gray-900">Rp {{ $latestPriceFormatted }} <span class="text-lg font-normal text-gray-500">/ gram</span></p>
                <!--<p class="text-sm text-gray-500 mt-2">Data diambil dari freegoldapi.com dan dikonversi ke IDR.</p>-->
            @endif
        </div>

        <!-- Chart Section -->
        @if(!isset($error) && isset($chartData))
        <div class="bg-white rounded-xl shadow-xl p-6 mt-8 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Tren Harga Emas Tahun Ini (IDR)</h3>
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
                                display: false,
                            },
                            title: {
                                display: true,
                                text: 'Pergerakan Harga Emas (IDR)'
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
            ">
                <canvas x-ref="priceChart" class="h-96"></canvas>
            </div>
        </div>
        @endif


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
