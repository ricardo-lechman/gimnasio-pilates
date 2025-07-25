<?php

namespace App\Http\Controllers;

abstract class Controller
{
    namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'comprobante_path' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $path = $request->file('comprobante_path')->store('comprobantes', 'public');

        return Payment::create([
            'booking_id' => $request->booking_id,
            'comprobante_path' => $path,
            'estado' => 'pendiente'
        ]);
    }
}

}
