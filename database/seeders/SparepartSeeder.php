<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_sparepart' => 'Oli Mesin AHM MPX-2 0.8L', 'harga' => 55000, 'stok' => 50],
            ['nama_sparepart' => 'Oli Gardan AHM 120ml', 'harga' => 18000, 'stok' => 45],
            ['nama_sparepart' => 'Kampas Rem Depan (Cakram) Beat/Vario', 'harga' => 55000, 'stok' => 30],
            ['nama_sparepart' => 'Kampas Rem Belakang (Tromol) Beat/Vario', 'harga' => 50000, 'stok' => 25],
            ['nama_sparepart' => 'V-Belt + Roller Set AHM K44', 'harga' => 165000, 'stok' => 15],
            ['nama_sparepart' => 'Busi NGK CPR9EA-9 (Matic Honda)', 'harga' => 25000, 'stok' => 40],
            ['nama_sparepart' => 'Filter Udara Honda Beat FI', 'harga' => 45000, 'stok' => 20],
            ['nama_sparepart' => 'Filter Udara Honda Vario 125/150', 'harga' => 55000, 'stok' => 15],
            ['nama_sparepart' => 'Bohlam Lampu Depan Standar', 'harga' => 25000, 'stok' => 35],
            ['nama_sparepart' => 'Aki Kering GS Astra GTZ4V', 'harga' => 230000, 'stok' => 10],
            ['nama_sparepart' => 'Ban Depan Federal 80/90-14 (Tubeless)', 'harga' => 185000, 'stok' => 12],
            ['nama_sparepart' => 'Ban Belakang Federal 90/90-14 (Tubeless)', 'harga' => 220000, 'stok' => 12],
        ];

        foreach ($data as $item) {
            Sparepart::create($item);
        }
    }
}
