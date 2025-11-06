<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Bahan Pangan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">MANAJEMEN DATA BAHAN PANGAN</h1>
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

            </div>
        </div>

        <!-- status notification -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                ✗ {{ session('error') }}
            </div>
        @endif

        <!-- add button -->
        <div class="mb-6 bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <form action="{{ route('admin.bahan-pangan.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="komoditas" class="block text-sm font-bold text-gray-700 mb-2">Komoditas</label>
                        <select name="komoditas" id="komoditas" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                            <option value="">Semua</option>
                            @foreach($komoditas as $item)
                                <option value="{{ $item }}" {{ request('komoditas') == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pasar" class="block text-sm font-bold text-gray-700 mb-2">Pasar</label>
                        <select name="pasar" id="pasar" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                            <option value="">Semua</option>
                            @foreach($pasars as $item)
                                <option value="{{ $item }}" {{ request('pasar') == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="provinsi" class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                            <option value="">Semua</option>
                            @foreach($provinsis as $item)
                                <option value="{{ $item }}" {{ request('provinsi') == $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                        Filter Data
                    </button>
                    <a href="{{ route('admin.bahan-pangan.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all inline-block">
                        Hapus Filter
                    </a>
                </div>
            </form>
        </div>

        <div class="mb-6 flex justify-end space-x-2">
            <a href="{{ route('admin.bahan-pangan.create') }}"
                class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                + Tambah Data Baru
            </a>
            <a href="{{ route('admin.bahan-pangan.export-excel') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                Export Excel
            </a>
            <a href="{{ route('admin.bahan-pangan.export-csv') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                Export CSV
            </a>
        </div>

        <div class="mb-6 bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Import Data Bahan Pangan</h2>
            <form action="{{ route('admin.bahan-pangan.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="file" class="block text-sm font-bold text-gray-700 mb-2">Pilih File Excel/CSV</label>
                    <input type="file" name="file" id="file" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                    Import Data
                </button>
            </form>
        </div>

        <!-- data table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-800 text-white">
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Komoditas</th>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Harga (Rp)</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left">Provinsi</th>
                            <th class="py-3 px-4 text-left">Kabupaten</th>
                            <th class="py-3 px-4 text-left">Kecamatan</th>
                            <th class="py-3 px-4 text-left">Pasar</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomor = 1;
                        @endphp
                        @forelse($bahanPangans as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $nomor++ }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $item->komoditas }}</td>
                                <td class="py-3 px-4">{{ $item->tanggal->format('d/m/Y') }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">{{ $item->kategori }}</td>
                                <td class="py-3 px-4">{{ $item->provinsi }}</td>
                                <td class="py-3 px-4">{{ $item->kabupaten }}</td>
                                <td class="py-3 px-4">{{ $item->kecamatan }}</td>
                                <td class="py-3 px-4">{{ $item->pasar }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <!-- edit button -->
                                        <a href="{{ route('admin.bahan-pangan.edit', $item->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded transition-all text-sm">
                                            Ubah
                                        </a>
                                        <!-- delete button -->
                                        <form action="{{ route('admin.bahan-pangan.destroy', $item->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded transition-all text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-4 px-4 text-center text-gray-500">Tidak ada data yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
