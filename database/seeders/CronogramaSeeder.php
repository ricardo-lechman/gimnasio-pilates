<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cronograma;
use Carbon\Carbon;

class CronogramaSeeder extends Seeder
{
    public function run(): void
    {
        $dias = 7; // Número de días que quieres generar
        $turnos = [
            ['08:00', '09:30'],
            ['09:30', '11:00'],
            ['11:00', '12:30'],
            ['12:30', '14:00'],
            ['14:00', '15:30'],
            ['15:30', '17:00'],
            ['17:00', '18:30'],
            ['18:30', '20:00'],
        ];

        for ($i = 0; $i < $dias; $i++) {
            $fecha = Carbon::today()->addDays($i)->toDateString();

            foreach ($turnos as $turno) {
                Cronograma::firstOrCreate([
                    'date' => $fecha,
                    'start_time' => $turno[0],
                    'end_time' => $turno[1],
                ]);
            }
        }

        $this->command->info("Seeder de cronogramas ejecutado: $dias días con 8 turnos cada uno.");
    }
}



