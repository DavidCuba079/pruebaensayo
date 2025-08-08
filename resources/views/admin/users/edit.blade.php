@extends('admin.layout')

@section('title', 'Editar Usuario: ' . $user->name)

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                <i class="bi bi-pencil-square mr-2"></i> Editar Usuario: {{ $user->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-eye-fill mr-1"></i> Ver Detalles
                </a>
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="bi bi-trash-fill mr-1"></i> Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div class="space-y-6">
                    <!-- Información Básica -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="bi bi-person-badge mr-2"></i> Información Básica
                        </h3>
                        
                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-person text-gray-400"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Ej: Juan Pérez">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Correo Electrónico -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="ejemplo@dominio.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Contraseña
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="password"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="•••••••• (dejar en blanco para no cambiar)">
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">Deja este campo en blanco si no deseas cambiar la contraseña.</p>
                            @enderror
                        </div>
                        
                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirmar Contraseña
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-lock-fill text-gray-400"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="bi bi-info-circle mr-2"></i> Información Adicional
                        </h3>
                        
                        <!-- Teléfono -->
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Teléfono
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                </div>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('phone') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="+51 999 999 999">
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- DNI -->
                        <div class="mb-4">
                            <label for="dni" class="block text-sm font-medium text-gray-700">
                                DNI
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-card-text text-gray-400"></i>
                                </div>
                                <input type="text" name="dni" id="dni" value="{{ old('dni', $user->dni) }}" maxlength="8"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('dni') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="12345678">
                            </div>
                            @error('dni')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Dirección -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Dirección
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 pt-2 flex items-start pointer-events-none">
                                    <i class="bi bi-geo-alt text-gray-400"></i>
                                </div>
                                <textarea name="address" id="address" rows="2"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md @error('address') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Av. Ejemplo 123">{{ old('address', $user->address) }}</textarea>
                            </div>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Columna Derecha -->
                <div class="space-y-6">
                    <!-- Rol y Estado -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="bi bi-shield-lock mr-2"></i> Rol y Estado
                        </h3>
                        
                        <!-- Rol -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Rol del Usuario <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('role') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="trainer" {{ old('role', $user->role) == 'trainer' ? 'selected' : '' }}>Entrenador</option>
                                <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>Miembro</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Estado -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="status" id="status" value="1" 
                                    {{ old('status', $user->status) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="status" class="ml-2 block text-sm text-gray-700">
                                    Usuario activo
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Los usuarios inactivos no podrán iniciar sesión en el sistema.
                            </p>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fechas importantes -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Información del sistema</h4>
                            <div class="space-y-1 text-sm text-gray-500">
                                <div class="flex justify-between">
                                    <span>Registrado el:</span>
                                    <span class="font-medium text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Última actualización:</span>
                                    <span class="font-medium text-gray-700">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($user->email_verified_at)
                                <div class="flex justify-between">
                                    <span>Correo verificado:</span>
                                    <span class="font-medium text-gray-700">{{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span>Último inicio de sesión:</span>
                                    <span class="font-medium text-gray-700">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Foto de Perfil -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="bi bi-camera mr-2"></i> Foto de Perfil
                        </h3>
                        
                        <div class="flex items-center">
                            <div class="mr-4">
                                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    <img id="profile-photo-preview" src="{{ $user->profile_photo_url }}" 
                                        alt="Foto de perfil de {{ $user->name }}" 
                                        class="h-full w-full object-cover">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="profile_photo" class="cursor-pointer">
                                    <span class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="bi bi-upload mr-2"></i> Cambiar foto
                                    </span>
                                    <input id="profile_photo" name="profile_photo" type="file" class="sr-only" 
                                        accept="image/jpeg,image/png,image/jpg,image/gif" 
                                        onchange="previewProfilePhoto(this)">
                                </label>
                                <p class="mt-1 text-xs text-gray-500">
                                    Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                </p>
                                @if($user->profile_photo_path)
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="remove_photo" id="remove_photo" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-600">Eliminar foto actual</span>
                                    </label>
                                </div>
                                @endif
                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-info-circle-fill text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Los campos marcados con <span class="text-red-500">*</span> son obligatorios.
                                </p>
                                <p class="text-sm text-blue-700 mt-1">
                                    Deja la contraseña en blanco para mantener la actual.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="pt-5 border-t border-gray-200">
                <div class="flex justify-between">
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="bi bi-arrow-left mr-1"></i> Volver al listado
                        </a>
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="bi bi-x-lg mr-1"></i> Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="bi bi-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Vista previa de la foto de perfil
    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('profile-photo-preview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Validación del tamaño de la imagen
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('profile_photo');
        if (fileInput.files.length > 0) {
            const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
            if (fileSize > 2) {
                e.preventDefault();
                alert('El tamaño de la imagen no debe exceder los 2MB');
                return false;
            }
        }
        return true;
    });
    
    // Formatear número de teléfono
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = '+' + value;
            if (value.length > 3) {
                value = value.substring(0, 3) + ' ' + value.substring(3);
            }
            if (value.length > 7) {
                value = value.substring(0, 7) + ' ' + value.substring(7, 11);
            }
            if (value.length > 11) {
                value = value.substring(0, 11) + ' ' + value.substring(11, 15);
            }
        }
        e.target.value = value;
    });
    
    // Formatear DNI (solo números, máximo 8 dígitos)
    document.getElementById('dni').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').substring(0, 8);
    });
    
    // Manejar la opción de eliminar foto
    document.getElementById('remove_photo')?.addEventListener('change', function() {
        const preview = document.getElementById('profile-photo-preview');
        if (this.checked) {
            preview.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ $user->name }}') + '&color=7F9CF5&background=EBF4FF';
        } else {
            preview.src = '{{ $user->profile_photo_url }}';
        }
    });
</script>
@endpush
@endsection
