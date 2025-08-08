@extends('layouts.app')

@section('title','Crear Socio')

@push('css')
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
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
        background-color: #e6f3ff; /* Fondo celeste claro para campos de entrada */
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    .main-content-with-sidebar {
        margin-left: 260px; /* Ajusta este valor al ancho real de tu menú lateral */
        transition: margin-left 0.3s;
    }
    .sidebar-hidden .main-content-with-sidebar {
        margin-left: 0 !important;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
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
    /* Opcional: botón para mostrar/ocultar menú lateral */
    .menu-toggle-btn {
        position: fixed;
        top: 20px;
        left: 10px;
        z-index: 1050;
        background: #667eea;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<!-- Botón para mostrar/ocultar menú lateral -->
<button class="menu-toggle-btn" onclick="toggleSidebar()" title="Mostrar/Ocultar menú">
    <i class="bi bi-list"></i>
</button>
<div class="main-content-with-sidebar">
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">
            <i class="bi bi-person-plus-fill me-2"></i>Crear Socio
        </h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.members.index')}}">Socios</a></li>
            <li class="breadcrumb-item active">Crear Socio</li>
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
            <b>No se pudo guardar el socio por los siguientes motivos:</b>
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
                    <i class="bi bi-person-plus-fill me-2"></i>Información del Socio
                </h5>
            </div>
            
            <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- Información Personal -->
                    <div class="field-group">
                        <h6><i class="bi bi-person-badge-fill"></i>Datos Personales</h6>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="first_name" class="form-label">Nombres:</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="last_name" class="form-label">Apellidos:</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="dni" class="form-label">CI:</label>
                                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni') }}" required>
                                @error('dni')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="email" class="form-label">Correo electrónico:</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="phone" class="form-label">Teléfono:</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="birthdate" class="form-label">Fecha de nacimiento:</label>
                                <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="gender" class="form-label">Género:</label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <option value="male" {{ old('gender')=='male'?'selected':'' }}>Masculino</option>
                                    <option value="female" {{ old('gender')=='female'?'selected':'' }}>Femenino</option>
                                    <option value="other" {{ old('gender')=='other'?'selected':'' }}>Otro</option>
                                </select>
                                @error('gender')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label for="profile_photo" class="form-label">Foto de perfil:</label>
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control" accept="image/*">
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
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
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
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                                @error('emergency_contact_name')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="emergency_contact_phone" class="form-label">Teléfono de emergencia:</label>
                                <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                                @error('emergency_contact_phone')
                                <div class="error-message">{{'*'.$message}}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="health_notes" class="form-label">Notas de salud:</label>
                                <textarea name="health_notes" id="health_notes" class="form-control" rows="2">{{ old('health_notes') }}</textarea>
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
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                            <label class="form-label" for="status">Activo</label>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-save2-fill me-2"></i>Guardar Socio
                    </button>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary-custom">
                        <i class="bi bi-arrow-left me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Formateo automático del teléfono
    document.getElementById('phone')?.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.slice(0, 3) + '-' + value.slice(3);
            } else {
                value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
            }
        }
        this.value = value;
    });

    // Validación del formato del CI
    document.getElementById('dni')?.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Mostrar/Ocultar menú lateral (ajusta el selector según tu layout)
    function toggleSidebar() {
        // Ajusta el selector según tu layout real
        var sidebar = document.querySelector('.sidebar') ||
                      document.getElementById('sidebar') ||
                      document.querySelector('.sb-sidenav') ||
                      document.querySelector('.sb-nav-fixed .sb-sidenav');
        var mainContent = document.querySelector('.main-content-with-sidebar');
        if (sidebar && mainContent) {
            sidebar.classList.toggle('d-none');
            document.body.classList.toggle('sidebar-hidden');
        } else {
            alert('No se encontró el menú lateral. Ajusta el selector en toggleSidebar().');
        }
    }
</script>
@endpush