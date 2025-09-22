<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Cama;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with(['user', 'cama'])->get();
        $usuarios = User::all();
        $camas = Cama::all();

        return Inertia::render('Admin/Reservas/Index', [
            'reservas' => $reservas,
            'usuarios' => $usuarios,
            'camas'    => $camas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cama_id' => 'required|exists:camas,id',
            'fecha'   => 'required|date',
            'estado'  => 'required|in:activa,cancelada',
        ]);

        Reserva::create($request->all());

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva creada correctamente');
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cama_id' => 'required|exists:camas,id',
            'fecha'   => 'required|date',
            'estado'  => 'required|in:activa,cancelada',
        ]);

        $reserva->update($request->all());

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva actualizada correctamente');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva eliminada correctamente');
    }
}
