<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_pelanggan = [
            [
                'nama_pelanggan' => 'Bapak Joko',
                'no_telp'        => '081233445566',
                'alamat'         => 'Jl. Sudirman No. 10',
            ],
            [
                'nama_pelanggan' => 'Ibu Siti',
                'no_telp'        => '081988776655',
                'alamat'         => 'Perumahan Indah Permai Blok B2',
            ],
            [
                'nama_pelanggan' => 'Mas Dimas',
                'no_telp'        => '085611223344',
                'alamat'         => 'Jl. Melati Gang 3',
            ],
            [
                'nama_pelanggan' => 'Mbak Rina',
                'no_telp'        => '082299887766',
                'alamat'         => 'Apartemen Puncak Jaya, Tower A',
            ],
            [
                'nama_pelanggan' => 'Pak Haji Soleh',
                'no_telp'        => '081122223333',
                'alamat'         => 'Jl. Kebon Jeruk Raya No. 99',
            ],
        ];

        // Looping datanya dan masukkan ke database
        foreach ($data_pelanggan as $pelanggan) {
            Pelanggan::create($pelanggan);
        }
    }
}
