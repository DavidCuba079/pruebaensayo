<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos para cada módulo
        $permissions = [
            // Usuarios
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            // Socios
            'ver socios', 'crear socios', 'editar socios', 'eliminar socios',
            // Membresías
            'ver membresias', 'crear membresias', 'editar membresias', 'eliminar membresias', 'renovar membresias', 'cancelar membresias',
            // Tipos de membresía
            'ver tipos_membresia', 'crear tipos_membresia', 'editar tipos_membresia', 'eliminar tipos_membresia',
            // Check-ins
            'registrar_ingreso', 'ver_ingresos', 'reportes_ingresos',
            // Configuración
            'ver_configuracion', 'editar_configuracion',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $admin->givePermissionTo(Permission::all());

        $recepcionista = Role::firstOrCreate(['name' => 'Recepcionista']);
        $recepcionista->givePermissionTo([
            'ver socios', 'crear socios', 'editar socios',
            'ver membresias', 'crear membresias', 'editar membresias', 'renovar membresias',
            'ver tipos_membresia',
            'registrar_ingreso', 'ver_ingresos',
        ]);

        $entrenador = Role::firstOrCreate(['name' => 'Entrenador']);
        $entrenador->givePermissionTo([
            'ver socios',
            'ver_ingresos',
        ]);

        $socio = Role::firstOrCreate(['name' => 'Socio']);
        $socio->givePermissionTo([
            // Permisos limitados para el área de miembros
        ]);

        // Asignar rol de administrador al primer usuario
        $user = User::first();
        if ($user) {
            // Asegurarse de que el usuario no tenga roles duplicados
            $user->syncRoles(['Administrador']);
            
            // Asignar todos los permisos directamente al usuario (por si acaso)
            $user->syncPermissions(Permission::all());
            
            // Forzar la recarga de la caché de roles y permisos
            app()['cache']->forget('spatie.permission.cache');
        }
    }
}
