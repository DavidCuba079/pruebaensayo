<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users in the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = DB::table('users')->select('id', 'name', 'email', 'created_at')->get();
        
        if ($users->isEmpty()) {
            $this->info('No hay usuarios registrados en el sistema.');
            return 0;
        }
        
        $this->info('=== USUARIOS REGISTRADOS ===');
        
        $headers = ['ID', 'Nombre', 'Email', 'Fecha de Registro'];
        $rows = [];
        
        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at,
            ];
        }
        
        $this->table($headers, $rows);
        
        return 0;
    }
}
