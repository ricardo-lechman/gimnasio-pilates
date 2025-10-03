<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPagoController extends Controller
{
    public function index()
    {
        // Pagos solo del usuario autenticado
        $pagos = Pago::with('reserva')
            ->where('user_id', auth()->id())
            ->get();

        // Reservas solo del usuario autenticado
        $reservas = Reserva::where('user_id', auth()->id())->get();

        return Inertia::render('Users/Pagos/Index', [
            'pagos'    => $pagos,
            'reservas' => $reservas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'monto'      => 'required|numeric|min:0',
            'metodo_pago'=> 'required|string',
            'comprobante'=> 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'fecha_pago' => 'required|date',
            'estado'     => 'required|in:pendiente,confirmado,rechazado',
        ]);

        $path = null;
        if ($request->hasFile('comprobante')) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
        }

        Pago::create([
            'reserva_id' => $request->reserva_id,
            'user_id'    => auth()->id(),
            'monto'      => $request->monto,
            'metodo_pago'=> $request->metodo_pago,
            'comprobante'=> $path,
            'fecha_pago' => $request->fecha_pago,
            'estado'     => $request->estado,
        ]);

        return redirect()->route('users.pagos.index')
            ->with('success', 'Pago registrado correctamente');
    }

    public function update(Request $request, Pago $pago)
    {
        // Solo puede modificar su propio pago
        if ($pago->user_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'monto'      => 'required|numeric|min:0',
            'metodo_pago'=> 'required|string',
            'comprobante'=> 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'fecha_pago' => 'required|date',
            'estado'     => 'required|in:pendiente,confirmado,rechazado',
        ]);

        $data = [
            'reserva_id' => $request->reserva_id,
            'monto'      => $request->monto,
            'metodo_pago'=> $request->metodo_pago,
            'fecha_pago' => $request->fecha_pago,
            'estado'     => $request->estado,
        ];

        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago->update($data);

        return redirect()->route('users.pagos.index')
            ->with('success', 'Pago actualizado correctamente');
    }

    public function destroy(Pago $pago)
    {
        // Solo puede eliminar su propio pago
        if ($pago->user_id !== auth()->id()) {
            abort(403, 'No autorizado.');
        }

        $pago->delete();

        return redirect()->route('users.pagos.index')
            ->with('success', 'Pago eliminado correctamente');
    }
}
