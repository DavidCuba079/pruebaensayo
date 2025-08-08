@extends('admin.layout')

@section('title', 'Gestión de Permisos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Permisos</h2>
        @can('create', \Spatie\Permission\Models\Permission::class)
        <a href="{{ route('admin.permissions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md flex items-center">
            <i class="bi bi-plus-circle mr-2"></i> Nuevo Permiso
        </a>
        @endcan
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por nombre o descripción..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="w-full md:w-48">
                <label for="group" class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                <select name="group" id="group" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los grupos</option>
                    @foreach($groups as $group => $groupName)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            {{ $groupName }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md h-10 flex items-center">
                    <i class="bi bi-funnel mr-2"></i> Filtrar
                </button>
                
                @if(request()->has('search') || request()->has('group'))
                <a href="{{ route('admin.permissions.index') }}" class="ml-2 text-gray-600 hover:text-gray-800 flex items-center h-10 px-3 rounded-md border border-gray-300 bg-white">
                    <i class="bi bi-x-lg mr-1"></i> Limpiar
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($permissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grupo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Roles
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permissions as $permission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <i class="bi bi-key"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $permission->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $permission->group ?? 'Sin grupo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $permission->description ?? 'Sin descripción' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($permission->roles->take(3) as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500">Sin roles asignados</span>
                                    @endforelse
                                    
                                    @if($permission->roles->count() > 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $permission->roles->count() - 3 }} más
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @can('update', $permission)
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete', $permission)
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este permiso?');">
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
                {{ $permissions->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-shield-lock text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">No se encontraron permisos</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->has('search') || request()->has('group'))
                        No hay permisos que coincidan con los filtros aplicados.
                    @else
                        Aún no se han registrado permisos en el sistema.
                    @endif
                </p>
                <div class="mt-6">
                    @can('create', \Spatie\Permission\Models\Permission::class)
                    <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-plus-circle mr-2"></i> Crear Permiso
                    </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
