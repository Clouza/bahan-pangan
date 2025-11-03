<!DOCTYPE html>
<html>

<head>
    <title>Visualisasi Data - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    class="inline-block bg-gray-100 hover:bg-red-800 hover:text-white text-gray-800 font-bold py-2 px-6 rounded-lg transition-all border border-gray-300">
                    📦 Manajemen Bahan Pangan
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-block bg-gray-100 hover:bg-red-800 hover:text-white text-gray-800 font-bold py-2 px-6 rounded-lg transition-all border border-gray-300">
                    👥 Manajemen Pengguna
                </a>
                <a href="{{ route('admin.bahan-pangan.visualization') }}"
                    class="inline-block bg-green-600 text-white font-bold py-2 px-6 rounded-lg">
                    📊 Visualisasi
                </a>
            </div>
        </div>

        <!-- chart -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <canvas id="bahanPanganChart"></canvas>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('bahanPanganChart').getContext('2d');
            const bahanPangans = @json($bahanPangans);

            const labels = bahanPangans.map(item => item.komoditas);
            const data = bahanPangans.map(item => item.harga);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Harga Bahan Pangan (Rp)',
                        data: data,
                        backgroundColor: 'rgba(156, 22, 22, 0.7)',
                        borderColor: 'rgba(156, 22, 22, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grafik Harga Bahan Pangan',
                            font: {
                                size: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>
