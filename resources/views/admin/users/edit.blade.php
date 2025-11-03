<!DOCTYPE html>
<html>

<head>
    <title>Ubah Pengguna - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100">

    <!-- header -->
    <header class="bg-gradient-to-r from-gray-800 via-red-900 to-gray-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold text-center">UBAH PENGGUNA</h1>
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

        <!-- form -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Ubah Pengguna</h2>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- username -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pengguna</label>
                    <input type="text" name="username" required value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Masukkan nama pengguna">
                    @error('username')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Masukkan kata sandi baru">
                    @error('password')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- level user -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Level Pengguna</label>
                    <input type="text" name="leveluser" required value="{{ old('leveluser', $user->leveluser) }}"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none"
                        placeholder="Example: Dansat, anggota">
                    @error('leveluser')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- role -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Peran</label>
                    <select name="role" required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-2 focus:ring-red-800/20 outline-none">
                        <option value="">Pilih Peran</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Pengguna</option>
                    </select>
                    @error('role')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                <!-- submit button -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all">
                        Perbarui Pengguna
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all inline-block">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>
