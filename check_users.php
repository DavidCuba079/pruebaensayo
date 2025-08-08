<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Consulta directa a la base de datos
$users = DB::select('SELECT id, name, email, created_at FROM users');

echo "=== USUARIOS EN EL SISTEMA ===\n";
if (empty($users)) {
    echo "No hay usuarios registrados.\n";
} else {
    foreach ($users as $user) {
        echo "ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email} | Registrado: {$user->created_at}\n";
    }
}
