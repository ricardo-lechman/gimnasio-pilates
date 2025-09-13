<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Reserva;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $reserva = Reserva::all(); //  todas las reservas

        foreach ($reserva as $reserva) {
            $statusPago = collect(['pendiente', 'completado', 'rechazado'])->random();

            $pago = Pago::create([
                'reserva_id'  => $reserva->id,
                'file_path'  => 'comprobante_1.pdf',
                'verified'    => rand(0,1), 
                'status'      => $statusPago,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($statusPago === 'completado') {
                $reserva->status = 'confirmado';
            } elseif ($statusPago === 'rechazado') {
                $reserva->status = 'cancelado';
            } else {
                $reserva->status = 'pendiente';
            }

            $reserva->save();
        }
    }
}
