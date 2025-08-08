<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * El nombre del modelo en singular (para mensajes de error).
     *
     * @var string
     */
    protected $modelName = 'recurso';

    /**
     * Verifica si el usuario tiene los permisos necesarios.
     *
     * @param  string|array  $permissions
     * @param  string  $redirectRoute
     * @return \Illuminate\Http\Response|void
     */
    protected function checkPermissions($permissions, $redirectRoute = 'dashboard')
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!auth()->user()->can($permission)) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para realizar esta acción.'
                    ], 403);
                }

                return redirect()
                    ->route($redirectRoute)
                    ->with('error', 'No tienes permiso para realizar esta acción.');
            }
        }
    }

    /**
     * Devuelve una respuesta JSON de éxito.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = null, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message ?? 'Operación realizada con éxito.',
        ], $status);
    }

    /**
     * Devuelve una respuesta JSON de error.
     *
     * @param  string  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = null, $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message ?? 'Ha ocurrido un error al procesar la solicitud.',
        ], $status);
    }

    /**
     * Redirige con un mensaje de éxito.
     *
     * @param  string  $route
     * @param  string  $message
     * @param  array  $parameters
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithSuccess($route, $message = null, $parameters = [])
    {
        return redirect()
            ->route($route, $parameters)
            ->with('success', $message ?? 'Operación realizada con éxito.');
    }

    /**
     * Redirige con un mensaje de error.
     *
     * @param  string  $route
     * @param  string  $message
     * @param  array  $parameters
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithError($route, $message = null, $parameters = [])
    {
        return redirect()
            ->route($route, $parameters)
            ->with('error', $message ?? 'Ha ocurrido un error al procesar la solicitud.');
    }
}
