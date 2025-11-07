<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Traemos solo reservas pendientes o confirmadas del usuario
        $reservas = Reserva::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Users/Pagos/Index', [
            'pagos' => $pagos,
            'reservas' => $reservas,
            'user' => $user,
        ]);
    }

    // Registrar nuevo pago
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|max:255',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $comprobantePath = null;

        if ($request->hasFile('comprobante')) {
            $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
        }

        Pago::create([
            'reserva_id' => $validated['reserva_id'],
            'user_id' => $user->id,
            'monto' => $validated['monto'],
            'metodo_pago' => $validated['metodo_pago'],
            'comprobante' => $comprobantePath,
            'fecha_pago' => now()->toDateString(), // solo fecha
            'estado' => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Pago registrado correctamente.');
    }

    // Bloqueamos edición y eliminación para usuarios
    public function update() { abort(403, 'No autorizado'); }
    public function destroy() { abort(403, 'No autorizado'); }
}





