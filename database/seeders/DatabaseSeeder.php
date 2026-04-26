<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\MekanikSeeder;
use Database\Seeders\PelangganSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SparepartSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            SparepartSeeder::class,
            PelangganSeeder::class,
            MekanikSeeder::class,
            ServiceSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
