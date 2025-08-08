@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Rol</h2>
        <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
            <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Nombre del Rol -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Rol <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
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
                                <div class="bg-gray-50 px-4 py-2 border-b">
                                    <h4 class="text-sm font-medium text-gray-700">{{ $group }}</h4>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="permission-{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       type="checkbox" 
                                                       value="{{ $permission->id }}"
                                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('admin.roles.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar Rol
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
        
        form.addEventListener('submit', function(e) {
            const checkboxes = form.querySelectorAll('input[name="permissions[]"]:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Debe seleccionar al menos un permiso para el rol.');
                return false;
            }
            
            return true;
        });
    });
</script>
@endpush
@endsection
