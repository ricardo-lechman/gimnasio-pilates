<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SessionsSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos al usuario administrador
        $user = User::where('dni', '12345678')->first();

        if ($user) {
            DB::table('sessions')->insert([
                'user_id'       => $user->id,
                'ip_address'    => '127.0.0.1',
                'user_agent'    => 'Mozilla/5.0 (Seeder Test)',
                'payload'       => 'Datos de sesión de prueba',
                'last_activity' => time(),
            ]);
        }
    }
}
