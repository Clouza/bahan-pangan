<!DOCTYPE html>
<html>

<head>
    <title>Food Supply Management - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">FOOD SUPPLY DATA MANAGEMENT</h1>
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
        <div class="mb-6 flex justify-end">
            <a href="{{ route('admin.bahan-pangan.create') }}"
                class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                + Add New Data
            </a>
        </div>

        <!-- data table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-800 text-white">
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Commodity</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Price (Rp)</th>
                            <th class="py-3 px-4 text-left">Category</th>
                            <th class="py-3 px-4 text-center">Action</th>
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
                                <td class="py-3 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <!-- edit button -->
                                        <a href="{{ route('admin.bahan-pangan.edit', $item->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded transition-all text-sm">
                                            Edit
                                        </a>
                                        <!-- delete button -->
                                        <form action="{{ route('admin.bahan-pangan.destroy', $item->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this data?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded transition-all text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-4 text-center text-gray-500">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
