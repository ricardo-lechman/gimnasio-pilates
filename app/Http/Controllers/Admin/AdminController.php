<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select(
                'id',
                'name',
                'last_name',
                'email',
                'role',
                'telefono',
                'dni',
                'obra_social',
                'ficha_medica'
            )->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'dni' => 'required|integer|unique:users,dni',
            'telefono' => 'required|integer',
            'obra_social' => 'nullable|string|max:255',
            'ficha_medica' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'obra_social' => $validated['obra_social'] ?? null,
            'ficha_medica' => $validated['ficha_medica'] ?? null,
            'role' => 'user',
        ]);

        return back()->with('flash', ['message' => 'Usuario creado correctamente.']);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'dni' => 'required|integer|unique:users,dni,' . $user->id,
            'telefono' => 'required|integer',
            'obra_social' => 'nullable|string|max:255',
            'ficha_medica' => 'nullable|string|max:500',
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'dni' => $validated['dni'],
            'telefono' => $validated['telefono'],
            'obra_social' => $validated['obra_social'] ?? null,
            'ficha_medica' => $validated['ficha_medica'] ?? null,
            'role' => 'user',
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
