<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'nama_service' => 'Servis Ringan / Tune Up',
                'harga'       => 50000,
            ],
            [
                'nama_service' => 'Servis Besar / Overhaul',
                'harga'       => 250000,
            ],
            [
                'nama_service' => 'Jasa Ganti Oli',
                'harga'       => 15000,
            ],
            [
                'nama_service' => 'Jasa Ganti Kampas Rem',
                'harga'       => 20000,
            ],
            [
                'nama_service' => 'Pembersihan CVT',
                'harga'       => 40000,
            ],
            [
                'nama_service' => 'Turun Mesin',
                'harga'       => 450000,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
