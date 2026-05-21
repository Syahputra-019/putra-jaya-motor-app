<?php

namespace Database\Seeders;

use App\Models\Mekanik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MekanikSeeder extends Seeder
{
    public function run(): void
    {
        $dataMekanik = [
            [
                'nama_mekanik' => 'Mas Mekanik',
                'no_telp' => '081234567890',
                'spesialisasi' => 'Servis Rutin',
                'email' => 'mas.mekanik@gmail.com',
            ],
            [
                'nama_mekanik' => 'Pak Mekanik',
                'no_telp' => '081987654321',
                'spesialisasi' => 'Mesin',
                'email' => 'pak.mekanik@example.com',
            ],
        ];

        foreach ($dataMekanik as $mekanikData) {
            $user = User::updateOrCreate(
                ['email' => $mekanikData['email']],
                [
                    'name' => $mekanikData['nama_mekanik'],
                    'password' => Hash::make('password123'),
                    'role' => 'mekanik',
                ]
            );

            Mekanik::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_mekanik' => $mekanikData['nama_mekanik'],
                    'no_telp' => $mekanikData['no_telp'],
                    'spesialisasi' => $mekanikData['spesialisasi'],
                ]
            );
        }
    }
}
