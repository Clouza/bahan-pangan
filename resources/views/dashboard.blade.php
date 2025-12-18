<!doctype html>
<html>
<head>
    <title>Dashboard Hub</title>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-xl">
        <div class="container mx-auto px-4 flex flex-col items-center">
            <img src="{{ asset('assets/logo.png') }}" width="80" height="80" class="rounded-full border-4 border-white shadow-lg mb-3" />
            <h1 class="text-3xl font-bold text-center">PUSAT DATA PANGAN</h1>
            <p class="text-lg text-gray-200 text-center">SATUAN INTELIJEN KORBRIMOB POLRI</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 pt-12 pb-12">
        <!-- Welcome Message with Margin Top -->
        <div class="text-center mb-10 mt-8">
            <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ auth()->user()->username }}</h2>
            <p class="text-gray-500 mt-1">Silahkan pilih menu layanan di bawah ini.</p>
        </div>

        <!-- Menu Grid - More Compact with Gap -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            
            <!-- Price Monitoring -->
            <a href="{{ route('price-monitoring') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                <div class="bg-teal-600 h-2 w-full"></div>
                <div class="p-5 text-center">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        📊
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Monitoring Harga</h3>
                    <p class="text-gray-400 text-xs leading-tight">Peta disparitas & grafik varian komoditas.</p>
                </div>
            </a>

            <!-- Kurs Dollar -->
            <a href="{{ route('kurs.dollar') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                <div class="bg-blue-600 h-2 w-full"></div>
                <div class="p-5 text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        💲
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Kurs Dollar</h3>
                    <p class="text-gray-400 text-xs leading-tight">Update nilai tukar Rupiah terhadap USD.</p>
                </div>
            </a>

            <!-- Harga Emas -->
            <a href="{{ route('harga.emas') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                <div class="bg-yellow-500 h-2 w-full"></div>
                <div class="p-5 text-center">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        🏆
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Harga Emas</h3>
                    <p class="text-gray-400 text-xs leading-tight">Grafik perkembangan harga emas terkini.</p>
                </div>
            </a>

            <!-- Admin Panel (Conditional) -->
            @if (auth()->user()->role == 'admin')
            <a href="{{ route('admin.bahan-pangan.index') }}" class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                <div class="bg-red-700 h-2 w-full"></div>
                <div class="p-5 text-center">
                    <div class="w-12 h-12 bg-red-100 text-red-700 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        🛠️
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Admin Panel</h3>
                    <p class="text-gray-400 text-xs leading-tight">Manajemen data dan sistem aplikasi.</p>
                </div>
            </a>
            @endif

        </div>

        <!-- Logout -->
        <div class="mt-12 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-600 text-sm font-medium transition-colors">
                    Log Out dari Akun
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center text-gray-300 text-[10px]">
            &copy; {{ date('Y') }} Jl. Komjen Pol M. Jasin, Cimanggis, Depok 16451
        </div>
    </div>
</body>
</html>
