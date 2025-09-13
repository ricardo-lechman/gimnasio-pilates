<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cama;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CamaController extends Controller
{
    public function index()
    {
        // Trae todas las camas
        $camas = Cama::all(); 

        return Inertia::render('Admin/Cama/Index', [
            'camas' => $camas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
        ]);

        Cama::create($validated);

        return redirect()->route('admin.camas.index')
                         ->with('success', 'Cama creada con éxito.');
    }

    public function update(Request $request, Cama $cama)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
        ]);

        $cama->update($validated);

        return redirect()->route('admin.camas.index')
                         ->with('success', 'Cama actualizada.');
    }

    public function destroy(Cama $cama)
    {
        $cama->delete();

        return redirect()->route('admin.camas.index')
                         ->with('success', 'Cama eliminada.');
    }
}
