<?php

namespace App\Http\Controllers;

abstract class Controller
{
    namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'comprobante_path' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $path = $request->file('comprobante_path')->store('comprobantes', 'public');

        return Pago::create([
            'reserva_id' => $request->reserva_id,
            'comprobante_path' => $path,
            'estado' => 'pendiente'
        ]);
    }
}

}
