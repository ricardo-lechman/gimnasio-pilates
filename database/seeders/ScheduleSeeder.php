<?php

// database/seeders/ScheduleSeeder.php

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $fecha = Carbon::today(); // Hoy
        $horaInicio = Carbon::createFromTime(8, 0, 0); // Primer turno: 08:00

        for ($i = 0; $i < 8; $i++) {
            $inicio = $horaInicio->copy()->addMinutes(90 * $i);
            $fin = $inicio->copy()->addMinutes(90);

            Schedule::create([
                'date' => $fecha,
                'start_time' => $inicio->format('H:i:s'),
                'end_time' => $fin->format('H:i:s'),
            ]);
        }
    }
}


