<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserReservaController extends Controller
{
    public function index()
    {
        // Solo las reservas del usuario autenticado, con cama y cronograma
        $reservas = Reserva::with(['cama', 'cronograma'])
            ->where('user_id', auth()->id())
            ->get();

        // Traigo todas las camas y cronogramas para el modal
        $camas = \App\Models\Cama::all();
        $cronogramas = \App\Models\Cronograma::all();

        return Inertia::render('Users/Reservas/Index', [
            'reservas' => $reservas,
            'camas' => $camas,
            'cronogramas' => $cronogramas,
        ]);
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'cama_id' => 'required|exists:camas,id',
            'cronograma_id' => 'required|exists:cronogramas,id',
            'status' => 'required|in:pendiente,confirmado,cancelado',
        ]);

        // Verificar que el usuario no tenga otra reserva para este cronograma
        $existe = Reserva::where('user_id', auth()->id())
            ->where('cronograma_id', $request->cronograma_id)
            ->exists();

        if ($existe) {
            return back()->withErrors(['cronograma_id' => 'Ya tienes una reserva para este turno.']);
        }

        // Crear la reserva
        Reserva::create([
            'user_id' => auth()->id(),
            'cama_id' => $request->cama_id,
            'cronograma_id' => $request->cronograma_id,
            'status' => $request->status,
        ]);

        return redirect()->route('users.reservas.index')
            ->with('success', 'Reserva creada correctamente');
    }

    public function destroy(Reserva $reserva)
    {
        // Solo puede eliminar su propia reserva
        if ($reserva->user_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }

        $reserva->delete();

        return redirect()->route('users.reservas.index')
            ->with('success', 'Reserva eliminada correctamente');
    }
}


