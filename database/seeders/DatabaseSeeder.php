<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'phone' => '123456789',
                'dni' => '12345678',
                'address' => 'Dirección del gimnasio',
                'status' => true,
                'role' => 'admin',
            ]
        );

        // Ejecutar el seeder de roles y permisos
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        // Asignar rol de administrador al usuario creado
        $admin->assignRole('Administrador');
    }
}
