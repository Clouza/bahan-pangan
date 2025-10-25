@extends('layouts.app')

@section('header')
    <p class="text-[#171111] tracking-light text-[32px] font-bold leading-tight min-w-72">
        Dashboard
    </p>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('dashboard') }}" method="GET">
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Tanggal Awal
                                </p>
                                <input placeholder="Pilih Tanggal Awal" type="date" name="tanggal_awal"
                                    value="{{ request('tanggal_awal') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                            </label>
                        </div>
                        <div class="flex max-w-[480px] flex-wrap items-end gap-4 px-4 py-3">
                            <label class="flex flex-col min-w-40 flex-1">
                                <p class="text-[#171111] text-base font-medium leading-normal pb-2">
                                    Tanggal Akhir
                                </p>
                                <input placeholder="Pilih Tanggal Akhir" type="date" name="tanggal_akhir"
                                    value="{{ request('tanggal_akhir') }}"
                                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#171111] focus:outline-0 focus:ring-0 border border-[#e5dcdc] bg-white focus:border-[#e5dcdc] h-14 placeholder:text-[#876464] p-[15px] text-base font-normal leading-normal" />
                            </label>
                        </div>
                        <div class="flex justify-stretch">
                            <div class="flex flex-1 gap-3 flex-wrap px-4 py-3 justify-start">
                                <button type="submit"
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#9c1616] text-white text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Filter</span>
                                </button>
                                <a href="{{ route('dashboard') }}"
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#f4f0f0] text-[#171111] text-sm font-bold leading-normal tracking-[0.015em]">
                                    <span class="truncate">Reset</span>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="px-4 py-3 @container">
                        <div class="flex overflow-hidden rounded-xl border border-[#e5dcdc] bg-white">
                            <table class="flex-1">
                                <thead>
                                    <tr class="bg-white">
                                        <th
                                            class="px-4 py-3 text-left text-[#171111] w-[400px] text-sm font-medium leading-normal">
                                            Komoditas
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-[#171111] w-[400px] text-sm font-medium leading-normal">
                                            Harga Hari Ini
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-[#171111] w-[400px] text-sm font-medium leading-normal">
                                            Harga Kemarin
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-[#171111] w-[400px] text-sm font-medium leading-normal">
                                            Perubahan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Placeholder Data --}}
                                    <tr class="border-t border-t-[#e5dcdc]">
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#171111] text-sm font-normal leading-normal">
                                            Beras
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 12.000
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 11.500
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-red-500 text-sm font-normal leading-normal">
                                            5% ↑
                                        </td>
                                    </tr>
                                    <tr class="border-t border-t-[#e5dcdc]">
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#171111] text-sm font-normal leading-normal">
                                            Daging Sapi
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 120.000
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 115.000
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-red-500 text-sm font-normal leading-normal">
                                            4.3% ↑
                                        </td>
                                    </tr>
                                    <tr class="border-t border-t-[#e5dcdc]">
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#171111] text-sm font-normal leading-normal">
                                            Telur Ayam
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 25.000
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-[#876464] text-sm font-normal leading-normal">
                                            Rp 24.000
                                        </td>
                                        <td class="h-[72px] px-4 py-2 w-[400px] text-green-500 text-sm font-normal leading-normal">
                                            4% ↓
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
