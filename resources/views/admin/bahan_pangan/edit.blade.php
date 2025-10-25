<!DOCTYPE html>
<html>

<head>
    <title>Edit Food Supply Data - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">EDIT FOOD SUPPLY DATA</h1>
            <p class="text-center text-gray-200 mt-2">Administrator Panel</p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">

        <!-- admin navigation -->
        <div class="mb-6 bg-white rounded-xl shadow-lg p-4 border border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}"
                    class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                    ← Dashboard
                </a>
                <a href="{{ route('admin.bahan-pangan.index') }}"
                    class="inline-block bg-red-800 text-white font-bold py-2 px-6 rounded-lg">
                    📦 Food Supply Management
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-block bg-gray-100 hover:bg-red-800 hover:text-white text-gray-800 font-bold py-2 px-6 rounded-lg transition-all border border-gray-300">
                    👥 User Management
                </a>
            </div>
        </div>

        <!-- form -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Food Supply Data</h2>

            <form action="{{ route('admin.bahan-pangan.update', $bahanPangan->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- commodity -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Commodity</label>
                    <input type="text" name="komoditas" required
                        value="{{ old('komoditas', $bahanPangan->komoditas) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Example: Premium Rice">
                    @error('komoditas')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- date -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                    <input type="date" name="tanggal" required
                        value="{{ old('tanggal', $bahanPangan->tanggal->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                    @error('tanggal')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Price (Rp)</label>
                    <input type="number" name="harga" required step="1" min="0"
                        value="{{ old('harga', $bahanPangan->harga) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Example: 15000">
                    <small class="text-gray-500 block mt-1">* Enter price in rupiah (whole number)</small>
                    @error('harga')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- category -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <input type="text" name="kategori" required
                        value="{{ old('kategori', $bahanPangan->kategori) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Example: Grains">
                    @error('kategori')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- submit button -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                        Update Data
                    </button>
                    <a href="{{ route('admin.bahan-pangan.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all inline-block">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>
