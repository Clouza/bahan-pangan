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

            <!-- dropdown for data panel type -->
            <div class="flex justify-center items-center gap-3">
                <label for="subjects" class="font-semibold text-lg">Tipe Panel Data:</label>
                <select name="subjects" id="subjects"
                    class="px-4 py-2 rounded-lg bg-white text-gray-800 font-semibold border-2 border-gray-300 focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none transition-all cursor-pointer">
                    <option value="konsumen">Konsumen</option>
                    <option value="produsen">Produsen</option>
                </select>
            </div>
            @endif
        </div>
    </header>

    <!-- main container -->
    <div class="container mx-auto px-4 py-8">
        <!-- date filter form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                🔍 Filter Data Berdasarkan Tanggal
            </h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_awal" value="{{ $tanggal_awal }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none" />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggal_akhir }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none" />
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        Filter
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        Atur Ulang
                    </a>
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
</html>
