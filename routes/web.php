<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rutas protegidas por login y verificación de email
    Route::middleware(['auth'])->group(function () {

    // Rutas para usuarios con rol "user"
    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('User/Dashboard');
        })->name('dashboard');
    });

    // Rutas para administradores
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');
    });
});
