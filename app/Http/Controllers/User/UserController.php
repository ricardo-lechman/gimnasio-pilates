<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Users/Users/Index', [
            'users' => [[
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'dni' => $user->dni,
                'telefono' => $user->telefono,
                'obra_social' => $user->obra_social,
                'ficha_medica' => $user->ficha_medica,
                'role' => $user->role,
            ]],
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($user->id !== auth()->id()) {
            abort(403, 'No tienes permiso para actualizar este usuario.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'dni' => ['required', 'digits:8', 'unique:users,dni,' . $user->id],
            'telefono' => ['required', 'digits:10'],
            'obra_social' => ['nullable', 'string', 'max:255'],
            'ficha_medica' => ['nullable', 'string', 'max:2000'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'El correo electrónico ya se encuentra registrado.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique' => 'El DNI ya se encuentra registrado.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'obra_social' => $validated['obra_social'] ?? null,
            'ficha_medica' => $validated['ficha_medica'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return back()->with('flash', ['message' => 'Usuario actualizado correctamente.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            abort(403, 'No autorizado para eliminar este usuario.');
        }

        auth()->logout();

        $user->delete();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada correctamente.');
    }
}
