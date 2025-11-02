<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['user', 'reserva.cama', 'reserva.cronograma'])->get();
        $usuarios = User::all();
        $reservas = Reserva::with(['cama', 'cronograma'])->get();

        return Inertia::render('Admin/Pagos/Index', [
            'pagos' => $pagos,
            'usuarios' => $usuarios,
            'reservas' => $reservas,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'user_id'    => 'required|exists:users,id',
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
            'user_id'    => $request->user_id,
            'monto'      => $request->monto,
            'metodo_pago'=> $request->metodo_pago,
            'comprobante'=> $path,
            'fecha_pago' => $request->fecha_pago,
            'estado'     => $request->estado,
        ]);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago registrado correctamente');
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'user_id'    => 'required|exists:users,id',
            'monto'      => 'required|numeric|min:0',
            'metodo_pago'=> 'required|string',
            'comprobante'=> 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'fecha_pago' => 'required|date',
            'estado'     => 'required|in:pendiente,confirmado,rechazado',
        ]);

        $data = $request->only(['reserva_id','user_id','monto','metodo_pago','fecha_pago','estado']);

        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago->update($data);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado correctamente');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado correctamente');
    }
}



