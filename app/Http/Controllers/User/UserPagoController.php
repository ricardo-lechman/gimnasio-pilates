<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class UserPagoController extends Controller
{
    // Mostrar lista de pagos del usuario
    public function index()
    {
        $user = Auth::user();

        $pagos = Pago::where('user_id', $user->id)
            ->with('reserva')
            ->latest()
            ->get();

        $reservas = Reserva::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Users/Pagos/Index', [
            'pagos' => $pagos,
            'reservas' => $reservas,
            'user' => $user,
        ]);
    }

    // Registrar nuevo pago (monto definido por admin / reserva)
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'metodo_pago' => 'required|string|max:255',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // Definir monto automáticamente según la reserva
        $reserva = Reserva::findOrFail($validated['reserva_id']);
        $monto = $reserva->precio ?? 0; // precio definido por admin en reserva

        $comprobantePath = null;
        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        Pago::create([
            'reserva_id' => $validated['reserva_id'],
            'user_id' => $user->id,
            'monto' => $monto,
            'metodo_pago' => $validated['metodo_pago'],
            'comprobante' => $comprobantePath,
            'fecha_pago' => now()->toDateString(),
            'estado' => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Pago registrado correctamente.');
    }

    // Bloqueamos edición y eliminación para usuarios
    public function update() { abort(403, 'No autorizado'); }
    public function destroy() { abort(403, 'No autorizado'); }
}


