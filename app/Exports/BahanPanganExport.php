<?php

namespace App\Exports;

use App\Models\BahanPangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BahanPanganExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BahanPangan::select('id', 'komoditas', 'tanggal', 'harga', 'kategori', 'provinsi', 'kabupaten', 'kecamatan', 'pasar', 'created_at', 'updated_at')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Komoditas',
            'Tanggal',
            'Harga',
            'Kategori',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Pasar',
            'Created At',
            'Updated At',
        ];
    }
}
