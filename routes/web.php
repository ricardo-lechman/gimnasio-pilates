<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\CamaController;

// Página de inicio: login si no hay sesión, dashboard según rol si está autenticado
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

// Redirección según rol después de login
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    return match (strtolower($user->role)) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('users.dashboardusers'),
        default => abort(403, 'Acceso no autorizado'),
    };
})->name('dashboard');

// Rutas protegidas por autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {

    // RUTAS PARA ADMINISTRADOR
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard');

        // Gestión de usuarios
        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');

        // Reservas
        Route::get('/reservas', fn () => Inertia::render('Admin/Reservas/Index'))->name('reservas.index');
        Route::get('/reservas/{id}', fn ($id) => Inertia::render('Admin/Reservas/Show', ['id' => $id]))->name('reservas.show');

        // Pagos
        Route::get('/pagos', fn () => Inertia::render('Admin/Pagos/Index'))->name('pagos.index');
        Route::get('/pagos/{id}', fn ($id) => Inertia::render('Admin/Pagos/Show', ['id' => $id]))->name('pagos.show');

        //Camas
        Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/camas', [CamaController::class, 'index'])->name('camas.index');
            Route::post('/camas', [CamaController::class, 'store'])->name('camas.store');
            Route::put('/camas/{cama}', [CamaController::class, 'update'])->name('camas.update');
            Route::delete('/camas/{cama}', [CamaController::class, 'destroy'])->name('camas.destroy');
        });


        // Otras secciones
        Route::get('/cama', fn () => Inertia::render('Admin/Cama/Index'))->name('cama.index');
        Route::get('/cronograma', fn () => Inertia::render('Admin/Cronograma/Index'))->name('cronograma.index');
        Route::get('/configuracion', fn () => Inertia::render('Admin/Configuracion/Index'))->name('configuracion.index');
        Route::get('/estadisticas', fn () => Inertia::render('Admin/Estadisticas/Index'))->name('estadisticas.index');
    });

    // RUTAS PARA USUARIO
    Route::middleware('role:user')->prefix('users')->name('users.')->group(function () {
        Route::get('/dashboard', fn () => Inertia::render('Users/DashboardUsers'))->name('dashboardusers');

        // Gestión de usuarios
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // Reservas
        Route::get('/reservas', fn () => Inertia::render('Users/Reservas/Index'))->name('reservas.index');
        Route::get('/reservas/{id}', fn ($id) => Inertia::render('Users/Reservas/Show', ['id' => $id]))->name('reservas.show');

        // Pagos
        Route::get('/pagos', fn () => Inertia::render('Users/Pagos/Index'))->name('pagos.index');
        Route::get('/pagos/{id}', fn ($id) => Inertia::render('Users/Pagos/Show', ['id' => $id]))->name('pagos.show');

        // Otras secciones
        Route::get('/cronograma', fn () => Inertia::render('Users/Cronograma/Index'))->name('cronograma.index');
    });
});
