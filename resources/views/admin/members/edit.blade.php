@extends('layouts.app')

@section('title', 'Editar Socio: ' . $member->full_name)

@push('css')
<style>
    /* Ocultar menú lateral completamente */
    .sidebar {
        display: none !important;
    }
    
    /* Ajustar contenido principal para usar todo el ancho */
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    .main-content-with-sidebar {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 20px;
    }
    
    /* Contenedor del formulario optimizado */
    .form-container {
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }
    
    /* Card del formulario */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    /* Estilos del formulario */
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        text-align: center;
        padding: 1.5rem;
    }
    
    /* Grupos de campos */
    .field-group {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }
    
    .field-group h6 {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    
    /* Optimización para pantalla completa */
    .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
        background-color: #f8f9fa; /* Fondo gris claro para campos de entrada */
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background-color: white;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #fff;
    }
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .btn-secondary-custom {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #fff;
    }
    .btn-secondary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }
    .field-group {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }
    .field-group h6 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    @media (max-width: 1200px) {
        .main-content-with-sidebar {
            margin-left: 0 !important;
        }
        .card {
            margin-left: 0;
            max-width: 100%;
        }
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
        .field-group {
            padding: 1rem;
        }
        .btn-primary-custom, .btn-secondary-custom {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .card {
            margin-left: 0;
            max-width: 100%;
        }
    }

</style>
@endpush

@section('content')
<div class="main-content-with-sidebar">
    <div class="form-container">
        <h1 class="mt-4 text-center">
            <i class="bi bi-pencil-square me-2"></i>Editar Socio
        </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.members.index')}}">Socios</a></li>
            <li class="breadcrumb-item active">Editar Socio</li>
        </ol>

        @if(session('error'))
        <div class="alert alert-danger">
            <b>Error:</b> {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <b>No se pudo actualizar el socio por los siguientes motivos:</b>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Editar Información del Socio
                </h5>
            </div>
            
            <form action="{{ route('admin.members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Información Personal -->
                    <div class="field-group">
                        <h6><i class="bi bi-person-badge-fill"></i>Datos Personales</h6>
                        
                        <!-- Foto del Socio -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="d-inline-block position-relative">
                                     @if($member->profile_photo_path && Storage::exists('public/' . $member->profile_photo_path))
                                        <img id="current-photo" src="{{ Storage::url($member->profile_photo_path) }}" 
                                             alt="{{ $member->full_name }}" 
                                             class="rounded-circle border border-3 border-primary" 
                                             style="width: 120px; height: 120px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    @else
                                        <div id="photo-placeholder" class="rounded-circle border border-3 border-secondary d-flex align-items-center justify-content-center bg-light" 
                                             style="width: 120px; height: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                            <i class="bi bi-person-fill text-secondary" style="font-size: 3rem;"></i>
                                        </div>
                                        <img id="current-photo" src="#" alt="Foto actual" class="rounded-circle border border-3 border-primary d-none" 
                                             style="width: 120px; height: 120px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    @endif
                                    <div class="position-absolute bottom-0 end-0">
                                        <span class="badge bg-primary rounded-pill p-2">
                                            <i class="bi bi-camera-fill"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h5 class="mb-1 text-primary">{{ $member->full_name }}</h5>
                                    <small class="text-muted">{{ $member->email ?? 'Sin correo' }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="first_name" class="form-label">Nombres:</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $member->first_name) }}" required>
                                @error('first_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="last_name" class="form-label">Apellidos:</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $member->last_name) }}" required>
                                @error('last_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="dni" class="form-label">CI:</label>
                                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni', $member->dni) }}" required>
                                @error('dni')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $member->email) }}" required>
                                @error('email')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="phone" class="form-label">Teléfono:</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                                @error('phone')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="birth_date" class="form-label">Fecha de nacimiento:</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}" required>
                                @error('birth_date')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="gender" class="form-label">Género:</label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <option value="male" {{ old('gender', $member->gender)=='male'?'selected':'' }}>Masculino</option>
                                    <option value="female" {{ old('gender', $member->gender)=='female'?'selected':'' }}>Femenino</option>
                                    <option value="other" {{ old('gender', $member->gender)=='other'?'selected':'' }}>Otro</option>
                                </select>
                                @error('gender')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="profile_photo" class="form-label">Foto de perfil:</label>
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">
                                @if($member->profile_photo_path)
                                    <small class="text-muted d-block mt-1">Foto actual: <a href="{{ Storage::url($member->profile_photo_path) }}" target="_blank">Ver imagen</a></small>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                        <label class="form-check-label" for="remove_photo">
                                            Eliminar foto actual
                                        </label>
                                    </div>
                                @endif
                                @error('profile_photo')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="field-group">
                        <h6><i class="bi bi-geo-alt-fill"></i>Dirección</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="address" class="form-label">Dirección:</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $member->address) }}">
                                @error('address')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contacto de Emergencia y Salud -->
                    <div class="field-group">
                        <h6><i class="bi bi-exclamation-triangle-fill"></i>Emergencia y Salud</h6>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label for="emergency_contact_name" class="form-label">Nombre contacto de emergencia:</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="emergency_contact_phone" class="form-label">Teléfono de emergencia:</label>
                                <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                                @error('emergency_contact_phone')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="health_notes" class="form-label">Notas de salud:</label>
                                <textarea name="health_notes" id="health_notes" class="form-control" rows="2">{{ old('health_notes', $member->health_notes) }}</textarea>
                                @error('health_notes')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="field-group">
                        <h6><i class="bi bi-check-circle-fill"></i>Estado</h6>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $member->status) ? 'checked' : '' }}>
                            <label class="form-label" for="status">Activo</label>
                        </div>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="card-footer text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('admin.members.index') }}" class="btn btn-secondary-custom">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-save me-2"></i>Actualizar Socio
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Vista previa de la foto de perfil
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                // Obtener elementos
                const currentPhoto = document.getElementById('current-photo');
                const placeholder = document.getElementById('photo-placeholder');
                
                // Mostrar la nueva imagen en la vista previa principal
                if (currentPhoto) {
                    currentPhoto.src = event.target.result;
                    currentPhoto.classList.remove('d-none');
                }
                
                // Ocultar el placeholder si existe
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
                
                // Mostrar información del archivo seleccionado
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                const info = document.createElement('small');
                info.className = 'text-success d-block mt-1';
                info.textContent = `Nueva imagen seleccionada: ${fileName} (${fileSize} MB)`;
                
                // Remover info anterior si existe
                const existingInfo = e.target.parentNode.querySelector('.text-success');
                if (existingInfo) {
                    existingInfo.remove();
                }
                
                e.target.parentNode.appendChild(info);
                
                // Desmarcar la opción de eliminar foto si estaba marcada
                const removePhotoCheckbox = document.getElementById('remove_photo');
                if (removePhotoCheckbox) {
                    removePhotoCheckbox.checked = false;
                }
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Manejar la opción de eliminar foto
    const removePhotoCheckbox = document.getElementById('remove_photo');
    if (removePhotoCheckbox) {
        removePhotoCheckbox.addEventListener('change', function() {
            const currentPhoto = document.getElementById('current-photo');
            const placeholder = document.getElementById('photo-placeholder');
            
            if (this.checked) {
                // Ocultar foto actual y mostrar placeholder
                if (currentPhoto) {
                    currentPhoto.classList.add('d-none');
                }
                if (placeholder) {
                    placeholder.classList.remove('d-none');
                }
            } else {
                // Mostrar foto actual y ocultar placeholder
                if (currentPhoto && currentPhoto.src !== window.location.href + '#') {
                    currentPhoto.classList.remove('d-none');
                }
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
        });
    }
    

</script>
@endpush

@endsection
