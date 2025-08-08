<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->select('id', 'name', 'email')->get();

echo "=== USUARIOS EN EL SISTEMA ===\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Nombre: {$user->name} | Email: {$user->email}\n";
}
