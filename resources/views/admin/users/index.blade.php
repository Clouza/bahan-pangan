<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Pengguna - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">MANAJEMEN PENGGUNA</h1>
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
                    class="inline-block bg-red-800 text-white font-bold py-2 px-6 rounded-lg">
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
        <div class="mb-6 flex justify-end">
            <a href="{{ route('admin.users.create') }}"
                class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                + Tambah Pengguna Baru
            </a>
        </div>

        <!-- data table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-red-800 text-white">
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama Pengguna</th>
                            <th class="py-3 px-4 text-left">Level Pengguna</th>
                            <th class="py-3 px-4 text-left">Peran</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomor = 1;
                        @endphp
                        @forelse($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $nomor++ }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $user->username }}</td>
                                <td class="py-3 px-4">{{ $user->leveluser }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <!-- edit button -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded transition-all text-sm">
                                            Ubah
                                        </a>
                                        <!-- delete button -->
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
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
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada data yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
