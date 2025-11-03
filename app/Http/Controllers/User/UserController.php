<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Muestra los datos del usuario autenticado
     */
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Users/Users/Index', [
            'users' => [
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'dni' => $user->dni,
                    'telefono' => $user->telefono,
                    'obra_social' => $user->obra_social,
                    'ficha_medica' => $user->ficha_medica,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    /**
     * Actualiza los datos del usuario autenticado
     */
    public function update(Request $request, User $user)
    {
        // Validar que el usuario que se intenta actualizar es el mismo autenticado
        if ($user->id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este usuario.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'dni' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'obra_social' => 'nullable|string|max:255',
            'ficha_medica' => 'nullable|string|max:2000',
            'password' => 'nullable|string|min:6',
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'dni' => $validated['dni'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'obra_social' => $validated['obra_social'] ?? null,
            'ficha_medica' => $validated['ficha_medica'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return back()->with('flash', ['message' => 'Usuario actualizado correctamente.']);
    }

    /**
     * Elimina la cuenta del usuario autenticado
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Solo puede eliminarse a sí mismo
        if (auth()->id() !== $user->id) {
            abort(403, 'No autorizado para eliminar este usuario.');
        }

        // Cerrar sesión antes de borrar
        auth()->logout();

        // Eliminar cuenta
        $user->delete();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}
