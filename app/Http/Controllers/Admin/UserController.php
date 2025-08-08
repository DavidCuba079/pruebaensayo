<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    /**
     * El nombre del modelo en singular (para mensajes de error).
     *
     * @var string
     */
    protected $modelName = 'usuario';

    /**
     * Mostrar una lista de usuarios.
     */
    public function index()
    {
        // Verificar permisos
        $this->checkPermissions('ver_usuarios');
        
        $users = User::with('roles')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        // Verificar permisos
        $this->checkPermissions('crear_usuarios');
        
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Almacenar un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        // Verificar permisos
        $this->checkPermissions('crear_usuarios');
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'dni' => ['nullable', 'string', 'max:20', 'unique:users'],
            'address' => ['nullable', 'string'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'status' => ['required', 'boolean'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            // Crear el usuario
            $validated['password'] = Hash::make($validated['password']);
            
            // Manejar la carga de la foto de perfil
            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }
            
            $user = User::create($validated);
            
            // Asignar rol al usuario
            $user->assignRole($validated['role']);
            
            if ($request->expectsJson()) {
                return $this->successResponse($user, 'Usuario creado exitosamente.', 201);
            }
            
            return $this->redirectWithSuccess('admin.users.index', 'Usuario creado exitosamente.');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error al crear el usuario: ' . $e->getMessage());
            }
            
            return $this->redirectWithError('admin.users.create', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar el formulario para editar un usuario existente.
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar el formulario para editar un usuario.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar el usuario en la base de datos.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'dni' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'address' => ['nullable', 'string'],
            'role' => ['required', 'string', 'in:admin,trainer,member'],
            'status' => ['boolean'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Actualizar la contraseña si se proporciona
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Manejar la carga de la foto de perfil
        if ($request->hasFile('profile_photo')) {
            // Eliminar la foto anterior si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar un usuario de la base de datos.
     */
    public function destroy(User $user)
    {
        // No permitir eliminarse a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Eliminar la foto de perfil si existe
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
