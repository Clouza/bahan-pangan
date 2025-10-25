@extends('layouts.guest')

@section('content')
    <div class="relative flex h-auto min-h-screen w-full flex-col bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('assets/background.jpg') }}')">
        <div class="absolute inset-0 z-0 bg-black opacity-50"></div>
        <div class="relative z-10 flex flex-col h-full min-h-screen">
            <header class="p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-10 w-auto">
                    <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">
                        Sistem Informasi Data Pangan
                    </h2>
                </div>
                <a href="{{ route('login') }}" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 transition-colors">
                    Login
                </a>
            </header>

            <main class="flex-grow flex items-center justify-center text-center text-white">
                <div class="px-4">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">Selamat Datang</h1>
                    <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto">
                        Sistem Informasi untuk memonitoring data pangan strategis dalam rangka menjaga stabilitas keamanan.
                    </p>
                    <a href="{{ route('login') }}" class="bg-red-600 text-white font-bold py-3 px-6 rounded-lg text-lg hover:bg-red-700 transition-colors">
                        Mulai
                    </a>
                </div>
            </main>

            <footer class="p-4 text-center text-white text-sm">
                <p>&copy; {{ date('Y') }} Satuan Intelijen Korbrimob Polri. All rights reserved.</p>
            </footer>
        </div>
    </div>
@endsection
