@extends('layouts.app')

@section('header')
    <h3 class="text-[#171111] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4">
        Tambah Pengguna
    </h3>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Username
                                </p>
                                <input placeholder="Masukkan username" name="username" value="{{ old('username') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Password
                                </p>
                                <input placeholder="Masukkan password" type="password" name="password"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Level User
                                </p>
                                <input placeholder="Masukkan level user" name="leveluser" value="{{ old('leveluser') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('leveluser')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Role
                                </p>
                                <select name="role"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 bg-[image:--select-button-svg] placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal">
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex px-4 py-3 justify-start gap-3">
                            <button type="submit"
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#9c1616] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate">Simpan</span>
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#f4f0f0] text-[#171111] text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate">Batal</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
