<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Cama;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserReservaController extends Controller
{
    public function index()
    {
        // Solo las reservas del usuario autenticado
        $reservas = Reserva::with('cama')
            ->where('user_id', auth()->id())
            ->get();

        $camas = Cama::all();

        return Inertia::render('Users/Reservas/Index', [
            'reservas' => $reservas,
            'camas'    => $camas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cama_id' => 'required|exists:camas,id',
            'fecha'   => 'required|date',
            'estado'  => 'required|in:activa,cancelada',
        ]);

        Reserva::create([
            'user_id' => auth()->id(),
            'cama_id' => $request->cama_id,
            'fecha'   => $request->fecha,
            'estado'  => $request->estado,
        ]);

        return redirect()->route('users.reservas.index')
            ->with('success', 'Reserva creada correctamente');
    }

    public function update(Request $request, Reserva $reserva)
    {
        // Solo puede modificar su propia reserva
        if ($reserva->user_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'cama_id' => 'required|exists:camas,id',
            'fecha'   => 'required|date',
            'estado'  => 'required|in:activa,cancelada',
        ]);

        $reserva->update([
            'cama_id' => $request->cama_id,
            'fecha'   => $request->fecha,
            'estado'  => $request->estado,
        ]);

        return redirect()->route('users.reservas.index')
            ->with('success', 'Reserva actualizada correctamente');
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
