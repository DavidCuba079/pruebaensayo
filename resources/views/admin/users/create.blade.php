@extends('admin.layout')

@section('title', 'Crear Nuevo Usuario')

@push('styles')
<style>
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
    .login-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 900px;
        padding: 2.5rem;
        margin: 2rem 0;
    }
    .login-logo {
        text-align: center;
        margin-bottom: 2rem;
    }
    .login-logo i {
        font-size: 3rem;
        color: #2a5298;
        margin-bottom: 1rem;
    }
    .login-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        color: #7f8c8d;
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    .form-control, .form-select {
        height: 48px;
        border-radius: 8px;
        padding-left: 45px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        font-size: 0.95rem;
        background-color: #e6f3ff; /* Fondo celeste claro para campos de entrada */
    }
    .form-control:focus, .form-select:focus {
        border-color: #2a5298;
        box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
    }
    .input-group-text {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 40px;
        background: transparent;
        border: none;
        z-index: 10;
        color: #95a5a6;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #2a5298;
        color: white;
        border: none;
        height: 48px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 1rem;
    }
    .btn-primary:hover {
        background: #1e3c72;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        height: 48px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 1rem;
    }
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(90, 98, 104, 0.3);
    }
    textarea.form-control {
        height: auto;
        padding-top: 12px;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-person-plus-fill"></i>
            <h2 class="login-title">Nuevo Usuario</h2>
            <p class="login-subtitle">Complete el formulario para registrar un nuevo usuario</p>
        </div>
        
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            
            <div class="row g-4">
                <!-- Columna Izquierda -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge me-2"></i> Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                        
                            <!-- Nombre -->
                            <div class="mb-4">
                                <label for="name" class="form-label">
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Ej: Juan Pérez">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        
                            <!-- Correo Electrónico -->
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="ejemplo@dominio.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        
                            <!-- Contraseña -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="password" id="password" required
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                            </div>
                            
                            <!-- Confirmar Contraseña -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="form-control"
                                        placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i> Información Adicional
                            </h5>
                        </div>
                        <div class="card-body">
                        
                            <!-- Teléfono -->
                            <div class="mb-4">
                                <label for="phone" class="form-label">
                                    Teléfono
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="+51 999 999 999">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        
                            <!-- DNI -->
                            <div class="mb-4">
                                <label for="dni" class="form-label">
                                    DNI
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-card-text"></i>
                                    </span>
                                    <input type="text" name="dni" id="dni" value="{{ old('dni') }}" maxlength="8"
                                        class="form-control @error('dni') is-invalid @enderror"
                                        placeholder="12345678">
                                </div>
                                @error('dni')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        
                            <!-- Dirección -->
                            <div class="mb-4">
                                <label for="address" class="form-label">
                                    Dirección
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <textarea name="address" id="address" rows="2"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Av. Ejemplo 123">{{ old('address') }}</textarea>
                                </div>
                                @error('address')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    </div>
                </div>
                
                <!-- Columna Derecha -->
                <div class="col-md-6">
                    <!-- Rol y Estado -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-lock me-2"></i> Rol y Estado
                            </h5>
                        </div>
                        <div class="card-body">
                        
                            <!-- Rol -->
                            <div class="mb-4">
                                <label for="role" class="form-label">
                                    Rol del Usuario <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-badge"></i>
                                    </span>
                                    <select id="role" name="role" required
                                        class="form-select @error('role') is-invalid @enderror">
                                        <option value="" disabled selected>Selecciona un rol</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                        <option value="trainer" {{ old('role') == 'trainer' ? 'selected' : '' }}>Entrenador</option>
                                        <option value="member" {{ old('role') == 'member' || old('role') === null ? 'selected' : '' }}>Miembro</option>
                                    </select>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        
                            <!-- Estado -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                        name="status" id="status" value="1" 
                                        {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        Usuario activo
                                    </label>
                                </div>
                                <small class="text-muted">
                                    Los usuarios inactivos no podrán iniciar sesión en el sistema.
                                </small>
                            </div>
                    </div>
                    
                    </div>
                </div>
                
                <!-- Foto de Perfil -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="bi bi-camera me-2"></i> Foto de Perfil
                            </h5>
                        </div>
                        <div class="card-body">
                        
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <div class="position-relative d-inline-block">
                                        <div class="rounded-circle overflow-hidden border border-3 border-light" style="width: 120px; height: 120px;">
                                            <img id="profile-photo-preview" 
                                                src="https://ui-avatars.com/api/?name=N+U&color=7F9CF5&background=EBF4FF" 
                                                alt="Vista previa de la foto de perfil" 
                                                class="img-fluid h-100 w-100 object-cover">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="profile_photo" class="form-label">
                                            Seleccionar imagen
                                        </label>
                                        <input class="form-control @error('profile_photo') is-invalid @enderror" 
                                            type="file" 
                                            id="profile_photo" 
                                            name="profile_photo"
                                            accept="image/jpeg,image/png,image/jpg,image/gif" 
                                            onchange="previewProfilePhoto(this)">
                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <div class="form-text">
                                            Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-info-circle-fill text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3
                                <p class="text-sm text-blue-700">
                                    Los campos marcados con <span class="text-red-500">*</span> son obligatorios.
                                </p>
                                <p class="text-sm text-blue-700 mt-1">
                                    La contraseña debe tener al menos 8 caracteres.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="pt-5 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-x-lg mr-1"></i> Cancelar
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-save mr-1"></i> Guardar Usuario
                    </button>
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
</script>
@endpush
@endsection
