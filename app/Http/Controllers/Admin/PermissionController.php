<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        
        $query = Permission::withCount('roles');
        
        // Búsqueda
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro por grupo
        if ($group = request('group')) {
            $query->where('group', $group);
        }
        
        $permissions = $query->latest()->paginate(15);
        $groups = $this->getPermissionGroups();
        
        return view('admin.permissions.index', compact('permissions', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Permission::class);
        
        $groups = $this->getPermissionGroups();
        return view('admin.permissions.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'group' => $validated['group'],
            'description' => $validated['description'] ?? null,
            'guard_name' => 'web'
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permiso creado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        
        $groups = $this->getPermissionGroups();
        
        // Cargar la relación de roles para mostrar cuáles tienen este permiso
        $permission->load('roles');
        
        return view('admin.permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        
        // Si es un permiso del sistema, solo permitir actualizar la descripción
        $rules = [
            'description' => 'nullable|string|max:255'
        ];
        
        // Solo permitir actualizar nombre y grupo si no es un permiso del sistema
        if (!$permission->is_system) {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permission->id)
            ];
            $rules['group'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($rules);
        
        // Actualizar solo los campos permitidos
        $updateData = [];
        
        if (!$permission->is_system) {
            $updateData['name'] = $validated['name'];
            $updateData['group'] = $validated['group'];
        }
        
        if (isset($validated['description'])) {
            $updateData['description'] = $validated['description'];
        }
        
        if (!empty($updateData)) {
            $permission->update($updateData);
            
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permiso actualizado correctamente');
        }
        
        return redirect()
            ->route('admin.permissions.index')
            ->with('info', 'No se realizaron cambios en el permiso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        
        // Prevenir eliminación de permisos del sistema
        if ($permission->is_system) {
            return back()
                ->with('error', 'No se puede eliminar un permiso del sistema');
        }
        
        // Verificar si el permiso está siendo utilizado por algún rol
        if ($permission->roles()->count() > 0) {
            return back()
                ->with('error', 'No se puede eliminar el permiso porque está siendo utilizado por uno o más roles');
        }

        try {
            $permission->delete();
            
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permiso eliminado correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar permiso: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Ocurrió un error al intentar eliminar el permiso. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Get the list of permission groups.
     *
     * @return array
     */
    protected function getPermissionGroups()
    {
        return [
            'Usuarios' => 'Usuarios',
            'Roles' => 'Roles',
            'Permisos' => 'Permisos',
            'Socios' => 'Socios',
            'Membresías' => 'Membresías',
            'Clases' => 'Clases',
            'Pagos' => 'Pagos',
            'Reportes' => 'Reportes',
            'Configuración' => 'Configuración',
        ];
    }
}
