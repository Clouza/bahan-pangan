@extends('layouts.app')

@section('header')
    <p class="text-[#171111] tracking-light text-[32px] font-bold leading-tight min-w-72">
        Tambah Data Komoditas Pangan
    </p>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.bahan-pangan.store') }}" method="POST">
                        @csrf
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Komoditas
                                </p>
                                <input placeholder="Nama komoditas" name="komoditas" value="{{ old('komoditas') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('komoditas')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Tanggal
                                </p>
                                <input placeholder="Pilih tanggal" type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Harga
                                </p>
                                <input placeholder="Harga komoditas" type="number" name="harga" value="{{ old('harga') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('harga')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Kategori
                                </p>
                                <input placeholder="Kategori komoditas" name="kategori" value="{{ old('kategori') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                                @error('kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>
                        <div class="flex justify-stretch">
                            <div class="flex flex-1 gap-3 flex-wrap px-4 py-3 justify-start">
                                <button type="submit"
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#9c1616] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Simpan</span>
                                </button>
                                <a href="{{ route('admin.bahan-pangan.index') }}"
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#f4f0f0] text-[#171111] text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Batal</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
