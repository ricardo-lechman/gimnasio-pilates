<?php

namespace App\Http\Controllers;

abstract class Controller
{
    namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['schedule', 'bed'])->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        return Booking::create([
            'user_id' => Auth::id(),
            'bed_id' => $request->bed_id,
            'schedule_id' => $request->schedule_id,
            'status' => 'pendiente'
        ]);
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $booking->status = 'cancelado';
        $booking->save();

        return response()->json(['message' => 'Turno cancelado']);
    }
}

}
