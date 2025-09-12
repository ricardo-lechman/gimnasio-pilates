<?php

// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@pilates.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'dni' => '12345678',
            'telefono' => '123456789',
            'obra_social' => 'OSDE',
            'ficha_medica' => 'Sin observaciones',
        ]);

        // Usuario normal
        User::create([
            'name' => 'Usuario Prueba',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'telefono' => '3425111111',
            'dni' => '40123456',
            'obra_social' => 'Swiss Medical',
            'ficha_medica' => 'Sin observaciones',
        ]);

        // Varios usuarios fake con factory
        User::factory(10)->create();
    }
}

