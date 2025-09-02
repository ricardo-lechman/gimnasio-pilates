<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\UserController;

// Página de bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Ruta para redirigir /dashboard a /admin/dashboard y evitar 404
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Rutas protegidas por autenticación y verificación de email
Route::middleware(['auth', 'verified'])->group(function () {

    // Rutas del panel de administración (rol: admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard principal del admin
        Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

        // Gestión de usuarios
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Gestión de turnos/reservas
        Route::get('/reservas', fn () => Inertia::render('Admin/Reservas/Index'))->name('reservas.index');
        Route::get('/reservas/{id}', fn ($id) => Inertia::render('Admin/Reservas/Show', ['id' => $id]))->name('reservas.show');

        // Gestión de pagos
        Route::get('/pagos', fn () => Inertia::render('Admin/Pagos/Index'))->name('pagos.index');
        Route::get('/pagos/{id}', fn ($id) => Inertia::render('Admin/Pagos/Show', ['id' => $id]))->name('pagos.show');

        // Configuración de camas
        Route::get('/cama', fn () => Inertia::render('Admin/Cama/Index'))->name('cama.index');

        // Configuración de cronogramas
        Route::get('/cronograma', fn () => Inertia::render('Admin/Cronograma/Index'))->name('cronograma.index');

        // Configuración general del sistema
        Route::get('/configuracion', fn () => Inertia::render('Admin/Configuracion/Index'))->name('configuracion.index');

        // Estadísticas o reportes
        Route::get('/estadisticas', fn () => Inertia::render('Admin/Estadisticas/Index'))->name('estadisticas.index');
    });
});
