<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $reservas = Reserva::all();
        $users = User::where('role', 'user')->get();

        if ($reservas->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No hay reservas o usuarios para generar pagos.');
            return;
        }

        foreach ($reservas as $reserva) {
            Pago::create([
                'reserva_id' => $reserva->id,
                'user_id' => $users->random()->id,
                'monto' => 3500,
                'metodo_pago' => 'transferencia',
                'comprobante' => 'comprobantes/comprobante_' . $reserva->id . '.pdf',
                'fecha_pago' => Carbon::now()->subDays(rand(0, 10)),
                'estado' => 'confirmado',
            ]);
        }

        $this->command->info('Pagos generados correctamente con monto fijo de 3500.');
    }
}

