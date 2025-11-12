<?php

namespace App\Exports;

use App\Models\BahanPangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BahanPanganExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = BahanPangan::select('id', 'komoditas', 'tanggal', 'harga', 'kategori', 'provinsi', 'kabupaten', 'kecamatan', 'pasar', 'created_at', 'updated_at');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        return $query->get();
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
