<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

// Rutas de administración protegidas por autenticación y rol de administrador
Route::middleware(['auth'])->group(function () {
    // Rutas de roles
    Route::resource('roles', '\App\Http\Controllers\Admin\RoleController')
        ->except(['show'])
        ->names('roles');
    
    // Rutas de permisos
    Route::resource('permissions', '\App\Http\Controllers\Admin\PermissionController')
        ->except(['show'])
        ->names('permissions');
    
    // Rutas adicionales para la gestión de permisos
    Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])
        ->name('roles.permissions.edit');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
        ->name('roles.permissions.update');
});
