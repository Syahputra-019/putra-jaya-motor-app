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
        // 1. Akun Admin (Bisa akses semua karena God Mode)
        User::create([
            'name' => 'Bos Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Mekanik (Cuma bisa akses jadwal kerja)
        User::create([
            'name' => 'Mas Mekanik',
            'email' => 'mekanik@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'mekanik',
        ]);

        // 3. Akun Pelanggan (Cuma bisa akses booking)
        User::create([
            'name' => 'Bro Pelanggan',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
        ]);
    }
}
