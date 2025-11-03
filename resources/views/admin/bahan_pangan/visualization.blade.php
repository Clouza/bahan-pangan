<!DOCTYPE html>
<html>

<head>
    <title>Visualisasi Data Bahan Pangan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">VISUALISASI DATA BAHAN PANGAN</h1>
            <p class="text-center text-gray-200 mt-2">Panel Administrator</p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">

        <!-- admin navigation -->
        <div class="mb-6 bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}"
                    class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                    ← Dasbor
                </a>
                <a href="{{ route('admin.bahan-pangan.index') }}"
                    class="inline-block bg-red-800 text-white font-bold py-2 px-6 rounded-lg">
                    📦 Manajemen Bahan Pangan
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-block bg-gray-100 hover:bg-red-800 hover:text-white text-gray-800 font-bold py-2 px-6 rounded-lg transition-all border border-gray-300">
                    👥 Manajemen Pengguna
                </a>
                <a href="{{ route('admin.bahan-pangan.visualization') }}"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                    📊 Visualisasi
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="mb-6 bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <form action="{{ route('admin.bahan-pangan.visualization') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="provinsi" class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                        <select name="provinsi" id="provinsi"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                            <option value="">Semua Provinsi</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p }}" {{ $provinsi == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="komoditas" class="block text-sm font-bold text-gray-700 mb-2">Komoditas</label>
                        <select name="komoditas" id="komoditas"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                            <option value="">Semua Komoditas</option>
                            @foreach($commodities as $c)
                                <option value="{{ $c }}" {{ $komoditas == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                        Filter Grafik
                    </button>
                    <a href="{{ route('admin.bahan-pangan.visualization') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all inline-block">
                        Hapus Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Chart Container -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Tren Harga</h2>
            <canvas id="priceChart"></canvas>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const datasets = [];
            const labels = new Set();

            const colors = [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(201, 203, 207)'
            ];
            let colorIndex = 0;

            for (const provinsi in chartData) {
                for (const komoditas in chartData[provinsi]) {
                    const dataPoints = chartData[provinsi][komoditas];
                    const data = dataPoints.map(dp => dp.harga);
                    const currentLabels = dataPoints.map(dp => dp.tanggal);

                    currentLabels.forEach(label => labels.add(label));

                    datasets.push({
                        label: `${komoditas} (${provinsi})`,
                        data: data,
                        borderColor: colors[colorIndex % colors.length],
                        tension: 0.1,
                        fill: false
                    });
                    colorIndex++;
                }
            }

            const sortedLabels = Array.from(labels).sort();

            const ctx = document.getElementById('priceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sortedLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Tren Harga Komoditas Pangan'
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Harga (Rp)'
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>