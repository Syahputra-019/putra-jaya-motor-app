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
        User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'Bos Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'mekanik@gmail.com'], [
            'name' => 'Mas Mekanik',
            'email' => 'mekanik@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'mekanik',
        ]);

        User::updateOrCreate(['email' => 'pelanggan@gmail.com'], [
            'name' => 'Bro Pelanggan',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
        ]);
    }
}
