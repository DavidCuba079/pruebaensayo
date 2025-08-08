@extends('admin.layout')

@section('title', 'Crear Nuevo Permiso')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Permiso</h2>
        <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
            <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Nombre del Permiso -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Permiso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="Ej: create_users, edit_roles, etc."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Usa el formato: accion_recurso (ej: view_users, edit_roles)</p>
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
                            required>
                        <option value="">Selecciona un grupo</option>
                        @foreach($groups as $value => $label)
                            <option value="{{ $value }}" {{ old('group') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
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
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">Describe para qué sirve este permiso.</p>
                    @enderror
                </div>

                <!-- Guardar en caché -->
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
            </div>

            <!-- Acciones del Formulario -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('admin.permissions.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-save mr-1"></i> Guardar Permiso
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Validación del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        // Validar nombre del permiso (solo minúsculas, números y guiones bajos)
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', function(e) {
                this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
            });
        }
        
        // Validación al enviar el formulario
        if (form) {
            form.addEventListener('submit', function(e) {
                let valid = true;
                
                // Validar nombre
                if (!nameInput.value.trim()) {
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
                if (!groupSelect.value) {
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
