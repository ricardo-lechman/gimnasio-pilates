<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cama;

class CamaSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            Cama::create([
                'nombre' => 'Cama ' . $i,
            ]);
        }
    }
}

