<!DOCTYPE html>
<html>

<head>
    <title>Tambah Data Komoditas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">TAMBAH DATA KOMODITAS</h1>
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
                <a href="{{ route('admin.commodities.index') }}"
                    class="inline-block bg-red-800 text-white font-bold py-2 px-6 rounded-lg">
                    💎 Manajemen Komoditas
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-block bg-gray-100 hover:bg-red-800 hover:text-white text-gray-800 font-bold py-2 px-6 rounded-lg transition-all border border-gray-300">
                    👥 Manajemen Pengguna
                </a>
            </div>
        </div>

        <!-- form -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Data Komoditas Baru</h2>

            <form action="{{ route('admin.commodities.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Contoh: Emas Antam">
                    @error('name')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                    @error('date')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga</label>
                    <input type="number" name="price" required step="0.01" min="0" value="{{ old('price') }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Contoh: 1000000">
                    @error('price')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe</label>
                    <select name="type" required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                        <option value="" disabled selected>Pilih tipe</option>
                        <option value="perhiasan" {{ old('type') == 'perhiasan' ? 'selected' : '' }}>Perhiasan</option>
                        <option value="antam" {{ old('type') == 'antam' ? 'selected' : '' }}>Antam</option>
                        <option value="dolar" {{ old('type') == 'dolar' ? 'selected' : '' }}>Kurs Dolar</option>
                    </select>
                    @error('type')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- submit button -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                        Simpan Data
                    </button>
                    <a href="{{ route('admin.commodities.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all inline-block">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>