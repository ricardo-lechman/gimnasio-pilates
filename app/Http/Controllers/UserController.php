<?php

namespace App\Http\Controllers;

abstract class Controller
{
    namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return Auth::user();
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only([
            'nombre', 'telefono', 'dni', 'obra_social', 'ficha_medica'
        ]));

        return response()->json(['message' => 'Perfil actualizado']);
    }
}

}
