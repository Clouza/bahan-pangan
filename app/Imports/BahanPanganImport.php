<?php

namespace App\Imports;

use App\Models\BahanPangan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class BahanPanganImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Remove 'id', 'created_at', 'updated_at' from the row data
        unset($row['id']);
        unset($row['created_at']);
        unset($row['updated_at']);

        $tanggal = $row['tanggal'];
        if (is_numeric($tanggal)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal);
        } else {
            $tanggal = Carbon::parse($tanggal);
        }

        return new BahanPangan([
            'komoditas' => $row['komoditas'],
            'tanggal' => $tanggal,
            'harga' => $row['harga'],
            'kategori' => $row['kategori'],
            'provinsi' => $row['provinsi'] ?? null,
            'kabupaten' => $row['kabupaten'] ?? null,
            'kecamatan' => $row['kecamatan'] ?? null,
            'pasar' => $row['pasar'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'komoditas' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'harga' => ['required', 'integer', 'min:0'],
            'kategori' => ['required', 'string', 'max:255'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kabupaten' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'pasar' => ['nullable', 'string', 'max:255'],
        ];
    }
}
