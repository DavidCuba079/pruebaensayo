@extends('layouts.app')

@section('title','Crear Cliente')

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
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 1.5rem;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    .field-group {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }
    
    .field-group h6 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .field-group h6 i {
        margin-right: 0.5rem;
        color: #667eea;
    }
    
    /* Responsive adjustments */
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
    }
    
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">
        <i class="fas fa-user-plus me-2"></i>Crear Cliente
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear Cliente</li>
    </ol>

    <div class="card">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2"></i>Información del Cliente
            </h5>
        </div>
        
        <form action="{{ route('clientes.store') }}" method="post">
            @csrf
            <div class="card-body">
                
                <!-- Tipo de Persona -->
                <div class="field-group">
                    <h6><i class="fas fa-user-tag"></i>Tipo de Cliente</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tipo_persona" class="form-label">Tipo de cliente:</label>
                            <select class="form-select" name="tipo_persona" id="tipo_persona">
                                <option value="" selected disabled>Seleccione una opción</option>
                                <option value="natural" {{ old('tipo_persona') == 'natural' ? 'selected' : '' }}>Persona natural</option>
                                <option value="juridica" {{ old('tipo_persona') == 'juridica' ? 'selected' : '' }}>Persona jurídica</option>
                            </select>
                            @error('tipo_persona')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Personal -->
                <div class="field-group" id="box-razon-social">
                    <h6><i class="fas fa-user"></i>Información Personal</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label id="label-natural" for="razon_social" class="form-label">Nombres y apellidos:</label>
                            <label id="label-juridica" for="razon_social" class="form-label">Nombre de la empresa:</label>
                            <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}" placeholder="Ingrese el nombre completo">
                            @error('razon_social')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono')}}" placeholder="Ej: 591-12345678">
                            @error('telefono')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="contacto" class="form-label">Contacto:</label>
                            <input type="text" name="contacto" id="contacto" class="form-control" value="{{old('contacto')}}" placeholder="Nombre de contacto adicional">
                            @error('contacto')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="field-group">
                    <h6><i class="fas fa-map-marker-alt"></i>Dirección</h6>
                    <div class="row">
                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input required type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion')}}" placeholder="Ingrese la dirección completa">
                            @error('direccion')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Documento -->
                <div class="field-group">
                    <h6><i class="fas fa-id-card"></i>Documento de Identidad</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="documento_id" class="form-label">Tipo de documento:</label>
                            <select class="form-select" name="documento_id" id="documento_id">
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach ($documentos as $item)
                                <option value="{{$item->id}}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{$item->tipo_documento}}</option>
                                @endforeach
                            </select>
                            @error('documento_id')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="numero_documento" class="form-label">Número de documento:</label>
                            <input required type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento')}}" placeholder="Ingrese el número de documento">
                            @error('numero_documento')
                            <div class="error-message">{{'*'.$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="fas fa-save me-2"></i>Guardar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary-custom">
                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#tipo_persona').on('change', function() {
            let selectValue = $(this).val();
            if (selectValue == 'natural') {
                $('#label-juridica').hide();
                $('#label-natural').show();
            } else {
                $('#label-natural').hide();
                $('#label-juridica').show();
            }
            $('#box-razon-social').show();
        });
        
        // Formateo automático del teléfono
        $('#telefono').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                } else {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            $(this).val(value);
        });
    });
</script>
@endpush