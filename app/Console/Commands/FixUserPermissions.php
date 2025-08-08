<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix {email? : Email del usuario a actualizar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige los permisos de un usuario específico o del primer usuario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Obtener el usuario por email o el primer usuario
        $user = $email 
            ? User::where('email', $email)->first() 
            : User::first();
            
        if (!$user) {
            $this->error('No se encontró el usuario.');
            return 1;
        }

        $this->info("Actualizando permisos para: {$user->name} ({$user->email})");

        // Crear el rol de administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        
        // Crear todos los permisos si no existen
        $permissions = [
            // Usuarios
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'eliminar usuarios',
            // Socios
            'ver_socios', 'crear_socios', 'editar_socios', 'eliminar_socios',
            // Membresías
            'ver_membresias', 'crear_membresias', 'editar_membresias', 'eliminar_membresias', 
            'renovar_membresias', 'cancelar_membresias',
            // Tipos de membresía
            'ver_tipos_membresia', 'crear_tipos_membresia', 'editar_tipos_membresia', 'eliminar_tipos_membresia',
            // Check-ins
            'registrar_ingreso', 'ver_ingresos', 'reportes_ingresos',
            // Configuración
            'ver_configuracion', 'editar_configuracion',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al rol de administrador
        $adminRole->syncPermissions(Permission::all());

        // Asignar el rol de administrador al usuario
        $user->syncRoles([$adminRole->name]);
        
        // Asignar todos los permisos directamente al usuario (por si acaso)
        $user->syncPermissions(Permission::all());
        
        // Forzar la recarga de la caché de roles y permisos
        app()['cache']->forget('spatie.permission.cache');
        
        // Recargar la relación de permisos
        $user->load('permissions', 'roles');

        $this->info('Permisos actualizados correctamente:');
        $this->line('');
        $this->line("<fg=yellow>Roles:</> " . $user->getRoleNames()->implode(', '));
        $this->line("<fg=yellow>Permisos directos:</> " . $user->getDirectPermissions()->pluck('name')->implode(', '));
        $this->line("<fg=yellow>Todos los permisos:</> " . $user->getAllPermissions()->pluck('name')->implode(', '));
        
        return 0;
    }
}
