<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creamos el usuario maestro (ID 1) silenciosamente para que el sistema funcione
        User::updateOrCreate(
            ['id' => 1], // Buscamos si existe el ID 1
            [
                'name' => 'Administrador',
                'email' => 'admin@sistema.local',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}