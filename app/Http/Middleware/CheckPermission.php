<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si no se especifican permisos, permitir el acceso
        if (empty($permissions)) {
            return $next($request);
        }

        // Verificar si el usuario tiene alguno de los permisos requeridos
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        // Si el usuario no tiene ninguno de los permisos requeridos
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        // Redirigir a la página de inicio con un mensaje de error
        return redirect()->route('admin.dashboard')
            ->with('error', 'No tienes permiso para realizar esta acción.');
    }
}
