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

// Rutas protegidas por autenticación y verificación de email
Route::middleware(['auth', 'verified'])->group(function () {

    // Rutas del usuario normal (rol: user)
    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('User/Dashboard');
        })->name('dashboard');
    });

    // Rutas del panel de administración (rol: admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Panel principal
        Route::get('/', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

        // Gestión de usuarios
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios');

        // Gestión de turnos/reservas
        Route::get('/reservas', fn () =>
            Inertia::render('Admin/Reservas/Index')
        )->name('reservas.index');

        Route::get('/reservas/{id}', fn ($id) =>
            Inertia::render('Admin/Reservas/Show', ['id' => $id])
        )->name('reservas.show');

        // Gestión de pagos
        Route::get('/pagos', fn () =>
            Inertia::render('Admin/Pagos/Index')
        )->name('pagos.index');

        Route::get('/pagos/{id}', fn ($id) =>
            Inertia::render('Admin/Pagos/Show', ['id' => $id])
        )->name('pagos.show');

        // Configuración general del sistema
        Route::get('/configuracion', fn () =>
            Inertia::render('Admin/Configuracion/Index')
        )->name('configuracion.index');

        // Estadísticas o reportes
        Route::get('/estadisticas', fn () =>
            Inertia::render('Admin/Estadisticas/Index')
        )->name('estadisticas.index');
    });
});


