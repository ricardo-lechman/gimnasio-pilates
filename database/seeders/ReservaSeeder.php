<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservas;
use App\Models\User;
use App\Models\Cama;
use App\Models\Cronograma;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $camas = Cama::all();
        $cronogramas = Cronograma::all();

        
        foreach ($camas as $cama) {
            foreach ($cronogramas as $cronograma) {
                Reservas::create([
                    'user_id'       => $user->id, 
                    'cama_id'       => $cama->id,
                    'cronograma_id' => $cronograma->id,
                    'status'        => 'pendiente', // Inicializamos como pendiente
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
