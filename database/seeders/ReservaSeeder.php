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
        $usuarios = User::where('role', 'user')->pluck('id')->toArray();
        $camas = Cama::pluck('id')->toArray();
        $cronogramas = Cronograma::pluck('id')->toArray();

        $combinaciones = [];

        // Genera todas las combinaciones posibles
        foreach ($usuarios as $user_id) {
            foreach ($camas as $cama_id) {
                foreach ($cronogramas as $cronograma_id) {
                    $combinaciones[] = [
                        'user_id' => $user_id,
                        'cama_id' => $cama_id,
                        'cronograma_id' => $cronograma_id,
                    ];
                }
            }
        }

        // Mezcla las combinaciones y toma solo 12 sin repetir
        shuffle($combinaciones);
        $seleccionadas = array_slice($combinaciones, 0, 12);

        // Posibles estados para probar
        $estados = ['pendiente', 'confirmado', 'cancelado'];

        foreach ($seleccionadas as $combo) {
            Reserva::create([
                'user_id' => $combo['user_id'],
                'cama_id' => $combo['cama_id'],
                'cronograma_id' => $combo['cronograma_id'],
                'status' => $estados[array_rand($estados)], // Estado aleatorio
            ]);
        }
    }
}

