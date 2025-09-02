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
    }
}

