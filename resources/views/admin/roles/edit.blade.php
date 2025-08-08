@extends('layouts.app')

@section('title', 'Editar Rol: ' . $role->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Editar Rol: {{ $role->name }}</h2>
        <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
            <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Nombre del Rol -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Rol <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $role->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           {{ $role->is_system ? 'disabled' : '' }}
                           required>
                    @if($role->is_system)
                        <p class="mt-1 text-sm text-gray-500">Los roles del sistema no pueden ser modificados.</p>
                    @endif
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permisos -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Permisos <span class="text-red-500">*</span>
                    </label>
                    
                    @error('permissions')
                        <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="space-y-4">
                        @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                            <div class="border rounded-md overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-700">{{ $group }}</h4>
                                    <div class="flex items-center">
                                        <span class="text-xs text-gray-500 mr-2">Seleccionar todo</span>
                                        <input type="checkbox" 
                                               class="group-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                               data-group="{{ Str::slug($group) }}">
                                    </div>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="permission-{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       type="checkbox" 
                                                       value="{{ $permission->id }}"
                                                       class="permission-checkbox focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                       data-group="{{ Str::slug($group) }}"
                                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                                       {{ $role->is_system ? 'disabled' : '' }}>
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="permission-{{ $permission->id }}" class="font-medium text-gray-700">
                                                    {{ $permission->name }}
                                                </label>
                                                @if($permission->description)
                                                    <p class="text-gray-500 text-xs">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Acciones del Formulario -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                @can('delete', $role)
                    @unless($role->is_system)
                    <button type="button"
                            onclick="confirmDelete()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="bi bi-trash mr-2"></i> Eliminar Rol
                    </button>
                    @endunless
                @else
                    <div></div> <!-- Espaciador -->
                @endcan
                
                <div class="flex space-x-3">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            {{ $role->is_system ? 'disabled' : '' }}>
                        Actualizar Rol
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Formulario de eliminación oculto -->
        @can('delete', $role)
            @unless($role->is_system)
            <form id="delete-form" action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endunless
        @endcan
    </div>
</div>

@push('scripts')
<script>
    // Confirmar eliminación
    function confirmDelete() {
        if (confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.')) {
            document.getElementById('delete-form').submit();
        }
    }
    
    // Seleccionar/deseleccionar todos los permisos de un grupo
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle para seleccionar todos los permisos de un grupo
        document.querySelectorAll('.group-checkbox').forEach(checkbox => {
            const group = checkbox.dataset.group;
            const checkboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]:not(:disabled)`);
            
            checkbox.addEventListener('change', function() {
                checkboxes.forEach(permCheckbox => {
                    permCheckbox.checked = this.checked;
                });
            });
            
            // Verificar si todos los permisos del grupo están seleccionados
            function checkGroup() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const someChecked = Array.from(checkboxes).some(cb => cb.checked);
                
                checkbox.checked = allChecked;
                checkbox.indeterminate = someChecked && !allChecked;
            }
            
            // Verificar el estado inicial
            checkGroup();
            
            // Verificar cuando cambia cualquier permiso del grupo
            checkboxes.forEach(cb => {
                cb.addEventListener('change', checkGroup);
            });
        });
        
        // Validación del formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
                
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('Debe seleccionar al menos un permiso para el rol.');
                    return false;
                }
                
                return true;
            });
        }
    });
</script>
@endpush
@endsection
