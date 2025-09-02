<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller
{
    public function show()
    {
        return Auth::user();
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only([
            'nombre',
            'telefono',
            'dni',
            'obra_social',
            'ficha_medica'
        ]));

        return response()->json(['message' => 'Perfil actualizado']);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:20',
            'obra_social' => 'nullable|string|max:255',
            'role' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['nombre'],
            'dni' => $validated['dni'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'],
            'obra_social' => $validated['obra_social'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']), // 👈 bcrypt aquí
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }
}

