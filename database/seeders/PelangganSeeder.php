<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $dataPelanggan = [
            [
                'nama_pelanggan' => 'Bro Pelanggan',
                'no_telp' => '081233445566',
                'alamat' => 'Jl. Sudirman No. 10',
                'email' => 'pelanggan@gmail.com',
            ],
            [
                'nama_pelanggan' => 'Ibu Siti',
                'no_telp' => '081988776655',
                'alamat' => 'Perumahan Indah Permai Blok B2',
                'email' => 'ibu.siti@example.com',
            ],
            [
                'nama_pelanggan' => 'Mas Dimas',
                'no_telp' => '085611223344',
                'alamat' => 'Jl. Melati Gang 3',
                'email' => 'mas.dimas@example.com',
            ],
            [
                'nama_pelanggan' => 'Mbak Rina',
                'no_telp' => '082299887766',
                'alamat' => 'Apartemen Puncak Jaya, Tower A',
                'email' => 'mbak.rina@example.com',
            ],
            [
                'nama_pelanggan' => 'Pak Haji Soleh',
                'no_telp' => '081122223333',
                'alamat' => 'Jl. Kebon Jeruk Raya No. 99',
                'email' => 'pak.haji.soleh@example.com',
            ],
        ];

        foreach ($dataPelanggan as $pelangganData) {
            $user = User::updateOrCreate(
                ['email' => $pelangganData['email']],
                [
                    'name' => $pelangganData['nama_pelanggan'],
                    'password' => Hash::make('password123'),
                    'role' => 'pelanggan',
                ]
            );

            Pelanggan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_pelanggan' => $pelangganData['nama_pelanggan'],
                    'no_telp' => $pelangganData['no_telp'],
                    'alamat' => $pelangganData['alamat'],
                ]
            );
        }
    }
}
