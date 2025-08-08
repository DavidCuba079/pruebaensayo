@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Roles</h2>
        @can('create', \Spatie\Permission\Models\Role::class)
        <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
            <i class="bi bi-plus-circle mr-2"></i> Nuevo Rol
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Permisos
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuarios
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                                @if($role->is_system)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Sistema
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($role->permissions->take(3) as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500">Sin permisos asignados</span>
                                    @endforelse
                                    
                                    @if($role->permissions->count() > 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $role->permissions->count() - 3 }} más
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $role->users_count ?? 0 }} usuarios
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @can('update', $role)
                                    <a href="{{ route('admin.roles.edit', $role) }}" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete', $role)
                                    <form action="{{ route('admin.roles.destroy', $role) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $roles->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-people text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No hay roles registrados</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Comienza creando un nuevo rol para asignar permisos a los usuarios.
                </p>
                <div class="mt-6">
                    @can('create', \Spatie\Permission\Models\Role::class)
                    <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-plus-circle mr-2"></i> Nuevo Rol
                    </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
