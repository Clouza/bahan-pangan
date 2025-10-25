@extends('layouts.guest')

@section('content')
    <div class="relative flex h-auto min-h-screen w-full flex-col items-center justify-center bg-cover bg-center bg-no-repeat p-4" style="background-image: url('{{ asset('assets/background.jpg') }}')">
        <div class="absolute inset-0 z-0 bg-black opacity-50"></div>
        <div class="relative z-10 flex w-full max-w-md flex-col rounded-xl bg-white bg-opacity-95 p-8 shadow-xl">
            <div class="mb-6 flex flex-col items-center">
                <div class="mb-4 flex h-24 w-24 items-center justify-center">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="h-auto w-full">
                </div>
                <h2 class="text-center text-3xl font-bold leading-tight text-[var(--text-dark)]">
                    Sistem Informasi Data Pangan
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Satuan Intelijen Korbrimob Polri
                </p>
            </div>

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <label class="flex flex-col">
                        <input
                            class="form-input flex h-12 w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg border border-[var(--input-border)] bg-[var(--input-bg)] p-3 text-[var(--text-dark)] placeholder:text-gray-500 focus:border-[var(--primary-blue)] focus:outline-none focus:ring-0 text-base font-normal leading-normal"
                            placeholder="Username" name="username" value="{{ old('username') }}" required autofocus />
                    </label>
                    <label class="flex flex-col">
                        <input
                            class="form-input flex h-12 w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg border border-[var(--input-border)] bg-[var(--input-bg)] p-3 text-[var(--text-dark)] placeholder:text-gray-500 focus:border-[var(--primary-blue)] focus:outline-none focus:ring-0 text-base font-normal leading-normal"
                            placeholder="Password" type="password" name="password" required
                            autocomplete="current-password" />
                    </label>

                    <div class="mt-6 flex flex-col gap-3">
                        <button type="submit"
                            class="flex h-12 w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-red-600 text-base font-bold leading-normal tracking-[0.015em] text-white transition-colors hover:bg-red-700">
                            <span class="truncate">Login</span>
                        </button>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="flex h-12 w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg border border-[var(--primary-blue)] bg-transparent text-base font-bold leading-normal tracking-[0.015em] text-[var(--primary-blue)] transition-colors hover:bg-[var(--primary-blue)] hover:text-white">
                                <span class="truncate">Register</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
