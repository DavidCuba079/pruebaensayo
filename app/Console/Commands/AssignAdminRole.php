<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-admin {email} {--remove : Remove admin role instead of adding it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign or remove admin role to/from a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $remove = $this->option('remove');

        // Buscar el usuario por email
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró ningún usuario con el email: {$email}");
            return 1;
        }

        // Verificar si el rol de administrador existe
        $adminRole = Role::where('name', 'Administrador')->first();
        
        if (!$adminRole) {
            $this->error('El rol de Administrador no existe en el sistema.');
            return 1;
        }

        if ($remove) {
            // Remover rol de administrador
            if ($user->hasRole('Administrador')) {
                $user->removeRole('Administrador');
                $this->info("Se ha removido el rol de Administrador del usuario: {$user->email}");
            } else {
                $this->info("El usuario {$user->email} no tiene el rol de Administrador.");
            }
        } else {
            // Asignar rol de administrador
            if ($user->hasRole('Administrador')) {
                $this->info("El usuario {$user->email} ya tiene el rol de Administrador.");
            } else {
                $user->assignRole('Administrador');
                $this->info("Se ha asignado el rol de Administrador al usuario: {$user->email}");
            }
        }

        return 0;
    }
}
