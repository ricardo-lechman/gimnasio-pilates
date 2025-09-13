<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cama;
use App\Models\Cronograma;
use App\Models\Reserva;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        // Traemos todos los usuarios que NO sean admin
        $usuarios = User::where('role', 'user')->get();

        $camas = Cama::all();
        $cronogramas = Cronograma::all();

        foreach ($usuarios as $user) {
            foreach ($camas as $cama) {
                foreach ($cronogramas as $cronograma) {
                    // Evitamos duplicados
                    Reserva::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'cama_id' => $cama->id,
                            'cronograma_id' => $cronograma->id,
                        ],
                        [
                            'status' => 'pendiente',
                        ]
                    );
                }
            }
        }
    }
}
