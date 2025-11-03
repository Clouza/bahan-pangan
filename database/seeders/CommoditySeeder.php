<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commodity;
use Carbon\Carbon;

class CommoditySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commodities = [
            ['name' => 'Emas', 'type' => 'logam mulia'],
            ['name' => 'Perak', 'type' => 'logam mulia'],
            ['name' => 'Minyak Mentah', 'type' => 'energi'],
            ['name' => 'Gas Alam', 'type' => 'energi'],
            ['name' => 'Kopi Robusta', 'type' => 'pertanian'],
            ['name' => 'Kopi Arabika', 'type' => 'pertanian'],
        ];

        foreach ($commodities as $commodity) {
            for ($i = 0; $i < 30; $i++) { // Seed data for the last 30 days
                Commodity::create([
                    'name' => $commodity['name'],
                    'date' => Carbon::now()->subDays($i),
                    'price' => rand(500, 10000) / 100,
                    'type' => $commodity['type'],
                ]);
            }
        }
    }
}
