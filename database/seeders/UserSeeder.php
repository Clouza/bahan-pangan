<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'DansatIntel',
            'password' => Hash::make('1609'),
            'leveluser' => 'Dansat',
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'KaurOps',
            'password' => Hash::make('1234'),
            'leveluser' => 'anggota',
            'role' => 'user',
        ]);
    }
}