<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Cama;
use App\Models\Cronograma;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with(['user', 'cama', 'cronograma'])
            ->join('cronogramas', 'reservas.cronograma_id', '=', 'cronogramas.id')
            ->orderBy('cronogramas.date', 'asc')
            ->select('reservas.*')
            ->get();

        $usuarios = User::where('role', 'user')->get();
        $camas = Cama::all();
        $cronogramas = Cronograma::all();

        return Inertia::render('Admin/Reservas/Index', [
            'reservas' => $reservas,
            'usuarios' => $usuarios,
            'camas' => $camas,
            'cronogramas' => $cronogramas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cama_id' => 'required|exists:camas,id',
            'cronograma_id' => 'required|exists:cronogramas,id',
            'status' => 'required|in:pendiente,confirmado,cancelado',
        ]);

        // Validar disponibilidad de la cama en ese cronograma
        $ocupada = Reserva::where('cama_id', $request->cama_id)
            ->where('cronograma_id', $request->cronograma_id)
            ->exists();

        if ($ocupada) {
            return back()->withErrors(['cama_id' => 'La cama ya está reservada para este turno.']);
        }

        Reserva::create($request->all());

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva creada correctamente.');
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cama_id' => 'required|exists:camas,id',
            'cronograma_id' => 'required|exists:cronogramas,id',
            'status' => 'required|in:pendiente,confirmado,cancelado',
        ]);

        // Validar disponibilidad de la cama en ese cronograma (excluyendo la reserva actual)
        $ocupada = Reserva::where('cama_id', $request->cama_id)
            ->where('cronograma_id', $request->cronograma_id)
            ->where('id', '!=', $reserva->id)
            ->exists();

        if ($ocupada) {
            return back()->withErrors(['cama_id' => 'La cama ya está reservada para este turno.']);
        }

        $reserva->update($request->all());

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('admin.reservas.index')->with('success', 'Reserva eliminada correctamente.');
    }
}