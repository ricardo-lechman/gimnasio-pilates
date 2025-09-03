<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagos;
use App\Models\Reservas;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $reservas = Reservas::all(); //  todas las reservas

        foreach ($reservas as $reserva) {
            $statusPago = collect(['pendiente', 'completado', 'rechazado'])->random();

            $pago = Pagos::create([
                'reserva_id'  => $reserva->id,
                'file_varchar'=> 'comprobante_' . $reserva->id . '.pdf', 
                'verified'    => rand(0,1), 
                'status'      => $statusPago,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($statusPago === 'completado') {
                $reserva->status = 'confirmada';
            } elseif ($statusPago === 'rechazado') {
                $reserva->status = 'cancelada';
            } else {
                $reserva->status = 'pendiente';
            }

            $reserva->save();
        }
    }
}
