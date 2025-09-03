<?php

// database/seeders/CronogramaSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Cronograma;
use Carbon\Carbon;

class CronogramaSeeder extends Seeder
{
    public function run(): void
    {
        $fecha = Carbon::today(); // Hoy
        $horaInicio = Carbon::createFromTime(8, 0, 0); // Primer turno: 08:00

        for ($i = 0; $i < 8; $i++) {
            $inicio = $horaInicio->copy()->addMinutes(60 * $i);
            $fin = $inicio->copy()->addMinutes(60);

            Cronograma::create([
                'date' => $fecha,
                'start_time' => $inicio->format('H:i:s'),
                'end_time' => $fin->format('H:i:s'),
            ]);
        }
    }
}


