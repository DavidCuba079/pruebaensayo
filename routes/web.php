<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de prueba simple
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Ruta temporal para depurar permisos
Route::get('/debug-permissions', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'No hay usuario autenticado';
    }
    
    $permissions = [
        'ver_socios' => $user->can('ver_socios'),
        'ver_roles' => $user->hasRole('admin'),
        'all_permissions' => $user->getAllPermissions()->pluck('name'),
        'roles' => $user->getRoleNames()
    ];
    
    return response()->json($permissions);
})->middleware('auth');

// Ruta de prueba temporal
Route::get('/test-route', function () {
    return '¡La ruta de prueba funciona correctamente!';
});

// Authentication Routes
require __DIR__.'/auth.php';

// Ruta para verificar la configuración de PHP (solo accesible en desarrollo)
if (app()->environment('local')) {
    Route::get('/phpinfo', function() {
        phpinfo();
    });
}

// Ruta para verificar el acceso al sistema de archivos (solo accesible en desarrollo)
if (app()->environment('local')) {
    Route::get('/check-fs', function() {
        $storagePath = storage_path();
        $logsPath = storage_path('logs');
        $logFile = storage_path('logs/laravel.log');
        
        $result = [
            'storage_path' => $storagePath,
            'storage_exists' => file_exists($storagePath) ? 'Sí' : 'No',
            'logs_path' => $logsPath,
            'logs_exists' => file_exists($logsPath) ? 'Sí' : 'No',
            'log_file' => $logFile,
            'log_file_exists' => file_exists($logFile) ? 'Sí' : 'No',
            'is_writable' => is_writable($logsPath) ? 'Sí' : 'No',
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];
        
        return response()->json($result);
    });
}

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');

    // Módulo de Socios
    Route::prefix('members')->name('admin.members.')->middleware(['auth'])->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        
        // Ruta para exportar a Excel
        Route::get('/export', [\App\Http\Controllers\Admin\ExportMemberController::class, 'exportToExcel'])
            ->name('export')
            ->middleware('permission:ver_socios');
            
        Route::middleware('auth')->group(function () {
            Route::get('/create', [MemberController::class, 'create'])->name('create');
            Route::post('/', [MemberController::class, 'store'])->name('store');
        });
        
        Route::middleware('auth')->group(function () {
            Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
            Route::put('/{member}', [MemberController::class, 'update'])->name('update');
        });
        
        Route::get('/{member}', [MemberController::class, 'show'])->name('show');
        
        Route::delete('/{member}', [MemberController::class, 'destroy'])
            ->middleware('auth')
            ->name('destroy');
    });

    // Módulo de Clases
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('index');
        Route::get('/create', [ClassController::class, 'create'])->name('create');
        Route::post('/', [ClassController::class, 'store'])->name('store');
        Route::get('/{class}', [ClassController::class, 'show'])->name('show');
        Route::get('/{class}/edit', [ClassController::class, 'edit'])->name('edit');
        Route::put('/{class}', [ClassController::class, 'update'])->name('update');
        Route::delete('/{class}', [ClassController::class, 'destroy'])->name('destroy');
    });

    // Módulo de Pagos
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
    });

    // Módulo de Reportes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
    });

    // Módulo de Usuarios
    Route::prefix('admin/users')->name('admin.users.')->middleware(['auth', 'role:Administrador'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
    });



    // Rutas de Roles y Permisos están definidas en routes/admin.php
    require __DIR__.'/admin.php';

    // Perfil de usuario
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
