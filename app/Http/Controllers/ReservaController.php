<?php

namespace App\Http\Controllers;


use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function index()
    {
        return Reserva::with(['Cronograma', 'Cama'])->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'Cama_id' => 'required|exists:Camas,id',
            'Cronograma_id' => 'required|exists:Cronogramas,id',
        ]);

        return Reserva::create([
            'user_id' => Auth::id(),
            'Cama_id' => $request->Cama_id,
            'Cronograma_id' => $request->Cronograma_id,
            'status' => 'pendiente'
        ]);
    }

    public function cancel($id)
    {
        $Reserva = Reserva::findOrFail($id);
        if ($Reserva->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $Reserva->status = 'cancelado';
        $Reserva->save();

        return response()->json(['message' => 'Turno cancelado']);
    }
}
