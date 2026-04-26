<?php

namespace Database\Seeders;

use App\Models\Mekanik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MekanikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Siapkan data dummy mekanik andalan
        $data_mekanik = [
            [
                'nama_mekanik' => 'Cak Budi',
                'no_telp'        => '081234567890',
                'spesialisasi' => 'Mesin',
            ],
            [
                'nama_mekanik' => 'Mas Anto',
                'no_telp'        => '081987654321',
                'spesialisasi' => 'Servis Rutin',
            ],
            [
                'nama_mekanik' => 'Bang Jali',
                'no_telp'        => '085612349876',
                'spesialisasi' => 'Kelistrikan',
            ],
            [
                'nama_mekanik' => 'Kang Ujang',
                'no_telp'        => '082233445566',
                'spesialisasi' => 'Modifikasi',
            ],
            [
                'nama_mekanik' => 'Pakde Yanto',
                'no_telp'        => '081122334455',
                'spesialisasi' => 'Mesin',
            ],
        ];

        // Looping datanya dan masukkan ke database
        foreach ($data_mekanik as $mekanik) {
            Mekanik::create($mekanik);
        }
    }
}
