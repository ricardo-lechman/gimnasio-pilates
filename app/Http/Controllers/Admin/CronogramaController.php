<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cronograma;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class CronogramaController extends Controller
{
    public function index()
    {
        $cronogramas = Cronograma::orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return Inertia::render('Admin/Cronogramas/Index', [
            'cronogramas' => $cronogramas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Cronograma::create($request->only('date', 'start_time', 'end_time'));

        return redirect()->route('admin.cronogramas.index')
            ->with('success', 'Turno agregado correctamente.');
    }

    /**
     * Genera automáticamente los 8 turnos del día indicado.
     */
    public function generarTurnos(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $fecha = Carbon::parse($request->date);
        $inicio = Carbon::createFromTime(8, 0); // 08:00
        $turnos = [];

        for ($i = 0; $i < 8; $i++) {
            $inicioTurno = (clone $inicio)->addMinutes($i * 90);
            $finTurno = (clone $inicioTurno)->addMinutes(90);

            $turnos[] = [
                'date' => $fecha->format('Y-m-d'),
                'start_time' => $inicioTurno->format('H:i'),
                'end_time' => $finTurno->format('H:i'),
            ];
        }

        // Evita duplicar turnos ya existentes
        foreach ($turnos as $turno) {
            $existe = Cronograma::where('date', $turno['date'])
                ->where('start_time', $turno['start_time'])
                ->exists();

            if (!$existe) {
                Cronograma::create($turno);
            }
        }

        return redirect()->route('admin.cronogramas.index')
            ->with('success', 'Turnos del día generados correctamente.');
    }
}
