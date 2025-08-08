@extends('admin.layout')

@section('title', 'Editar Permiso: ' . $permission->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Editar Permiso: {{ $permission->name }}</h2>
        <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
            <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Nombre del Permiso -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Permiso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $permission->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required
                           {{ $permission->is_system ? 'disabled' : '' }}>
                    @if($permission->is_system)
                        <p class="mt-1 text-sm text-gray-500">Los permisos del sistema no pueden ser modificados.</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Usa el formato: accion_recurso (ej: view_users, edit_roles)</p>
                    @endif
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grupo -->
                <div class="mb-6">
                    <label for="group" class="block text-sm font-medium text-gray-700 mb-1">
                        Grupo <span class="text-red-500">*</span>
                    </label>
                    <select name="group" 
                            id="group"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('group') border-red-500 @enderror"
                            {{ $permission->is_system ? 'disabled' : '' }}
                            required>
                        <option value="">Selecciona un grupo</option>
                        @foreach($groups as $value => $label)
                            <option value="{{ $value }}" {{ old('group', $permission->group) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if($permission->is_system)
                        <input type="hidden" name="group" value="{{ $permission->group }}">
                    @endif
                    @error('group')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Agrupa permisos relacionados (ej: Usuarios, Roles, etc.)</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              {{ $permission->is_system ? 'disabled' : '' }}>{{ old('description', $permission->description) }}</textarea>
                    @if($permission->is_system)
                        <input type="hidden" name="description" value="{{ $permission->description }}">
                    @endif
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Describe para qué sirve este permiso.</p>
                    @enderror
                </div>

                <!-- Roles que tienen este permiso -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Roles con este permiso
                    </label>
                    @if($permission->roles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($permission->roles as $role)
                                <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                    <div class="flex items-center">
                                        <span class="h-3 w-3 rounded-full bg-blue-500 mr-2"></span>
                                        <span class="font-medium text-gray-900">{{ $role->name }}</span>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $role->permissions_count }} permisos
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-gray-50 rounded-md">
                            <p class="text-sm text-gray-500">Ningún rol tiene asignado este permiso.</p>
                        </div>
                    @endif
                </div>

                <!-- Guardar en caché -->
                @if(!$permission->is_system)
                <div class="flex items-center">
                    <input id="guard_in_cache" 
                           name="guard_in_cache" 
                           type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                           {{ old('guard_in_cache', true) ? 'checked' : '' }}>
                    <label for="guard_in_cache" class="ml-2 block text-sm text-gray-700">
                        Guardar en caché para mejor rendimiento
                    </label>
                </div>
                @endif
            </div>

            <!-- Acciones del Formulario -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between">
                @can('delete', $permission)
                    @unless($permission->is_system)
                    <button type="button"
                            onclick="confirmDelete()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="bi bi-trash mr-2"></i> Eliminar Permiso
                    </button>
                    @endunless
                @else
                    <div></div> <!-- Espaciador -->
                @endcan
                
                <div class="flex space-x-3">
                    <a href="{{ route('admin.permissions.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            {{ $permission->is_system ? 'disabled' : '' }}>
                        <i class="bi bi-save mr-1"></i> Actualizar Permiso
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Formulario de eliminación oculto -->
        @can('delete', $permission)
            @unless($permission->is_system)
            <form id="delete-form" action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="hidden">
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
        if (confirm('¿Estás seguro de que deseas eliminar este permiso? Esta acción no se puede deshacer.')) {
            document.getElementById('delete-form').submit();
        }
    }
    
    // Validación del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const nameInput = document.getElementById('name');
        
        // Validar nombre del permiso (solo minúsculas, números y guiones bajos)
        if (nameInput && !nameInput.disabled) {
            nameInput.addEventListener('input', function(e) {
                this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
            });
        }
        
        // Validación al enviar el formulario
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;
                
                // Validar nombre
                if (nameInput && !nameInput.disabled && !nameInput.value.trim()) {
                    valid = false;
                    const errorElement = nameInput.nextElementSibling;
                    if (!errorElement.classList.contains('text-red-600')) {
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-1 text-sm text-red-600';
                        errorMsg.textContent = 'El nombre del permiso es obligatorio.';
                        nameInput.parentNode.insertBefore(errorMsg, errorElement);
                    }
                }
                
                // Validar grupo
                const groupSelect = document.getElementById('group');
                if (groupSelect && !groupSelect.disabled && !groupSelect.value) {
                    valid = false;
                    const errorElement = groupSelect.nextElementSibling;
                    if (!errorElement.classList.contains('text-red-600')) {
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'mt-1 text-sm text-red-600';
                        errorMsg.textContent = 'Debes seleccionar un grupo.';
                        groupSelect.parentNode.insertBefore(errorMsg, errorElement);
                    }
                }
                
                if (!valid) {
                    e.preventDefault();
                    // Desplazarse al primer error
                    const firstError = form.querySelector('.text-red-600');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                return valid;
            });
        }
    });
</script>
@endpush
@endsection
