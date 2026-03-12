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
            ['name' => 'Oli Mesin AHM MPX-2 0.8L', 'price' => 55000, 'stock' => 50],
            ['name' => 'Oli Gardan AHM 120ml', 'price' => 18000, 'stock' => 45],
            ['name' => 'Kampas Rem Depan (Cakram) Beat/Vario', 'price' => 55000, 'stock' => 30],
            ['name' => 'Kampas Rem Belakang (Tromol) Beat/Vario', 'price' => 50000, 'stock' => 25],
            ['name' => 'V-Belt + Roller Set AHM K44', 'price' => 165000, 'stock' => 15],
            ['name' => 'Busi NGK CPR9EA-9 (Matic Honda)', 'price' => 25000, 'stock' => 40],
            ['name' => 'Filter Udara Honda Beat FI', 'price' => 45000, 'stock' => 20],
            ['name' => 'Filter Udara Honda Vario 125/150', 'price' => 55000, 'stock' => 15],
            ['name' => 'Bohlam Lampu Depan Standar', 'price' => 25000, 'stock' => 35],
            ['name' => 'Aki Kering GS Astra GTZ4V', 'price' => 230000, 'stock' => 10],
            ['name' => 'Ban Depan Federal 80/90-14 (Tubeless)', 'price' => 185000, 'stock' => 12],
            ['name' => 'Ban Belakang Federal 90/90-14 (Tubeless)', 'price' => 220000, 'stock' => 12],
        ];

        foreach ($data as $item) {
            Sparepart::create($item);
        }
    }
}
