<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanPangan;
use Carbon\Carbon;

class BahanPanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bahanPangans = [
            [
                'komoditas' => 'Beras',
                'kategori' => 'Serealia',
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Bandung',
                'kecamatan' => 'Cibiru',
                'pasar' => 'Pasar Induk Gedebage',
            ],
            [
                'komoditas' => 'Daging Ayam',
                'kategori' => 'Daging',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'kecamatan' => 'Wonokromo',
                'pasar' => 'Pasar Wonokromo',
            ],
            [
                'komoditas' => 'Telur Ayam',
                'kategori' => 'Telur',
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Jakarta Timur',
                'kecamatan' => 'Jatinegara',
                'pasar' => 'Pasar Jatinegara',
            ],
            [
                'komoditas' => 'Bawang Merah',
                'kategori' => 'Bumbu',
                'provinsi' => 'Jawa Tengah',
                'kabupaten' => 'Brebes',
                'kecamatan' => 'Brebes',
                'pasar' => 'Pasar Bawang Brebes',
            ],
            [
                'komoditas' => 'Cabai Merah',
                'kategori' => 'Bumbu',
                'provinsi' => 'Sumatera Barat',
                'kabupaten' => 'Agam',
                'kecamatan' => 'IV Angkek',
                'pasar' => 'Pasar Aur Kuning',
            ],
        ];

        foreach ($bahanPangans as $item) {
            for ($i = 0; $i < 30; $i++) { // Seed data for the last 30 days
                BahanPangan::create([
                    'komoditas' => $item['komoditas'],
                    'tanggal' => Carbon::now()->subDays($i),
                    'harga' => rand(10000, 50000),
                    'kategori' => $item['kategori'],
                    'provinsi' => $item['provinsi'],
                    'kabupaten' => $item['kabupaten'],
                    'kecamatan' => $item['kecamatan'],
                    'pasar' => $item['pasar'],
                ]);
            }
        }
    }
}
