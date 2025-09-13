<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cama;

class CamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            Cama::create([
                'nombre' => 'Cama ' . $i,
                'estado' => 'disponible', // todas arrancan disponibles
            ]);
        }
    }
}


