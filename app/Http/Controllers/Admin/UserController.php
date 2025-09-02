<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select('id', 'name', 'email', 'role')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['nombre'],
            'email' => $validated['email'],
            'role' => 'User', // Rol fijo "User"
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('flash', ['message' => 'Usuario creado correctamente.']);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $dataToUpdate = [
            'name' => $validated['nombre'],
            'email' => $validated['email'],
            'role' => 'User',
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return back()->with('flash', ['message' => 'Usuario actualizado correctamente.']);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('flash', ['message' => 'Usuario eliminado correctamente.']);
    }
}
