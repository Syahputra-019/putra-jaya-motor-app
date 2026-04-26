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
                'spesialisasi' => 'Mesin',
                'email' => 'mekanik@gmail.com',
            ],
            [
                'nama_mekanik' => 'Mas Anto',
                'no_telp' => '081987654321',
                'spesialisasi' => 'Servis Rutin',
                'email' => 'mas.anto@example.com',
            ],
            [
                'nama_mekanik' => 'Bang Jali',
                'no_telp' => '085612349876',
                'spesialisasi' => 'Kelistrikan',
                'email' => 'bang.jali@example.com',
            ],
            [
                'nama_mekanik' => 'Kang Ujang',
                'no_telp' => '082233445566',
                'spesialisasi' => 'Modifikasi',
                'email' => 'kang.ujang@example.com',
            ],
            [
                'nama_mekanik' => 'Pakde Yanto',
                'no_telp' => '081122334455',
                'spesialisasi' => 'Mesin',
                'email' => 'pakde.yanto@example.com',
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
