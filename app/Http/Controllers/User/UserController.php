<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    // Mostrar solo el usuario autenticado
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Users/Users/Index', [
            'users' => [
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ],
        ]);
    }

    // Actualizar solo el usuario autenticado
    public function update(Request $request, User $user)
    {
        // Validar que el usuario que se intenta actualizar es el autenticado
        if ($user->id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este usuario.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $dataToUpdate = [
            'name' => $validated['nombre'],
            'email' => $validated['email'],
            'role' => 'user', // conservar el rol original
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return back()->with('flash', ['message' => 'Usuario actualizado correctamente.']);
    }
}
