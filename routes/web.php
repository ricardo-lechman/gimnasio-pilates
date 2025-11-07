<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CamaController;
use App\Http\Controllers\Admin\CronogramaController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\Admin\PagoController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserReservaController;
use App\Http\Controllers\User\UserPagoController;

// Página de inicio: Redirige al login o al dashboard correspondiente.
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match (strtolower($user->role)) {
            'admin' => redirect()->route('admin.dashboard'),
            'user'  => redirect()->route('users.dashboardusers'),
            default => abort(403, 'Acceso no autorizado'),
        };
    }
    return redirect()->route('login');
});

// Redirección principal del dashboard según el rol del usuario.
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    return match (strtolower($user->role)) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('users.dashboardusers'),
        default => abort(403, 'Acceso no autorizado'),
    };
})->name('dashboard');

// ==========================================================
// RUTAS PARA ADMINISTRADOR
// ==========================================================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

        // CRUD Usuarios
        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');

        // CRUD Camas
        Route::get('/cama', [CamaController::class, 'index'])->name('camas.index');
        Route::post('/cama', [CamaController::class, 'store'])->name('camas.store');
        Route::put('/camas/{cama}', [CamaController::class, 'update'])->name('camas.update');
        Route::delete('/camas/{cama}', [CamaController::class, 'destroy'])->name('camas.destroy');

        // CRUD Reservas
        Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
        Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
        Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

        // CRUD Pagos
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
        Route::put('/pagos/{pago}', [PagoController::class, 'update'])->name('pagos.update');
        Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])->name('pagos.destroy');

        // Otras secciones del Admin
        Route::get('/cronograma', fn () => Inertia::render('Admin/Cronograma/Index'))->name('cronograma.index');
        Route::get('/configuracion', fn () => Inertia::render('Admin/Configuracion/Index'))->name('configuracion.index');
        Route::get('/estadisticas', fn () => Inertia::render('Admin/Estadisticas/Index'))->name('estadisticas.index');
    });

// ========================================================
// RUTAS PARA USUARIOS
// ========================================================
Route::middleware(['auth', 'verified', 'role:user'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {

        Route::get('/dashboard', fn () => Inertia::render('Users/DashboardUsers'))->name('dashboardusers');

        // Perfil de Usuario
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Reservas del Usuario
        Route::get('/reservas', [UserReservaController::class, 'index'])->name('reservas.index');
        Route::post('/reservas', [UserReservaController::class, 'store'])->name('reservas.store');
        Route::delete('/reservas/{reserva}', [UserReservaController::class, 'destroy'])->name('reservas.destroy');

        // Pagos del Usuario (solo listar y agregar)
        Route::get('/pagos', [UserPagoController::class, 'index'])->name('pagos.index');
        Route::post('/pagos', [UserPagoController::class, 'store'])->name('pagos.store');
        Route::get('/pagos/{id}', [UserPagoController::class, 'show'])->name('pagos.show');

        // Cronograma
        Route::get('/cronograma', fn () => Inertia::render('Users/Cronograma/Index'))->name('cronograma.index');
    });
