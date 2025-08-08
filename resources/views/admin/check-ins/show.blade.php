@extends('admin.layout')

@section('title', 'Detalles del Registro de Acceso')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles del Registro de Acceso</h1>
        <div>
            <a href="{{ route('admin.check-ins.index') }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
            <a href="{{ route('admin.check-ins.edit', $checkIn) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ route('admin.check-ins.destroy', $checkIn) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('¿Estás seguro de eliminar este registro de acceso?')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Información del miembro -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Miembro</h3>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $checkIn->member->profile_photo_url }}" 
                         alt="Foto de perfil" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h4>{{ $checkIn->member->full_name }}</h4>
                    <p class="text-muted">
                        <i class="fas fa-id-card"></i> DNI: {{ $checkIn->member->dni }}
                    </p>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.members.show', $checkIn->member) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> Ver Perfil
                        </a>
                        <a href="{{ route('admin.check-ins.member.history', $checkIn->member) }}" class="btn btn-outline-info">
                            <i class="fas fa-history"></i> Historial
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Información de la membresía -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Membresía</h3>
                </div>
                <div class="card-body">
                    @if($checkIn->membership)
                        <div class="text-center mb-3">
                            <span class="badge bg-primary p-2" style="font-size: 1.1em;">
                                {{ $checkIn->membership->membershipType->name }}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <strong><i class="far fa-calendar-alt mr-2"></i>Período:</strong><br>
                            {{ $checkIn->membership->start_date->format('d/m/Y') }} - 
                            {{ $checkIn->membership->end_date->format('d/m/Y') }}
                        </div>
                        
                        <div class="mb-2">
                            <strong><i class="fas fa-info-circle mr-2"></i>Estado:</strong><br>
                            @if($checkIn->membership->isActive())
                                <span class="badge bg-success">Activa</span>
                            @elseif($checkIn->membership->isExpired())
                                <span class="badge bg-danger">Expirada</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.memberships.show', $checkIn->membership) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> Ver detalles
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No se encontró información de la membresía.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Detalles del registro de acceso -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Registro</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-{{ $checkIn->access_type === 'check_in' ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $checkIn->access_type === 'check_in' ? 'sign-in-alt' : 'sign-out-alt' }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tipo de Acceso</span>
                                    <span class="info-box-number">
                                        {{ $checkIn->access_type === 'check_in' ? 'Entrada' : 'Salida' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="far fa-calendar"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Fecha y Hora</span>
                                    <span class="info-box-number">
                                        {{ $checkIn->check_in_at->format('d/m/Y H:i:s') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($checkIn->access_type === 'check_in' && $checkIn->checkOut())
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-purple">
                                        <i class="fas fa-stopwatch"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Duración de la Visita</span>
                                        <span class="info-box-number">
                                            {{ $checkIn->getFormattedDuration() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Hora de Salida</span>
                                        <span class="info-box-number">
                                            {{ $checkIn->checkOut()->check_in_at->format('H:i:s') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @elseif($checkIn->access_type === 'check_in')
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Este registro de entrada no tiene una salida registrada.
                                    
                                    <form action="{{ route('admin.check-ins.check-out', $checkIn) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm"
                                            onclick="return confirm('¿Registrar salida para {{ $checkIn->member->full_name }}?')">
                                            <i class="fas fa-sign-out-alt"></i> Registrar Salida
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        
                        @if($checkIn->access_type === 'check_out' && $checkIn->checkIn())
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Hora de Entrada</span>
                                        <span class="info-box-number">
                                            {{ $checkIn->checkIn()->check_in_at->format('H:i:s') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-purple">
                                        <i class="fas fa-stopwatch"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Duración de la Visita</span>
                                        <span class="info-box-number">
                                            {{ $checkIn->getFormattedDuration() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="mt-4">
                        <h5>Información Adicional</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">Registrado por:</th>
                                    <td>{{ $checkIn->user->name ?? 'Sistema' }}</td>
                                </tr>
                                <tr>
                                    <th>Dirección IP:</th>
                                    <td>{{ $checkIn->ip_address ?? 'No disponible' }}</td>
                                </tr>
                                <tr>
                                    <th>Dispositivo/Explorador:</th>
                                    <td>{{ $checkIn->user_agent ?? 'No disponible' }}</td>
                                </tr>
                                @if($checkIn->notes)
                                    <tr>
                                        <th>Notas:</th>
                                        <td>{{ $checkIn->notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Registro relacionado (entrada/salida) -->
            @if($relatedRecord)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $checkIn->access_type === 'check_in' ? 'Salida' : 'Entrada' }} Relacionada
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    {{ $relatedRecord->check_in_at->format('d/m/Y H:i:s') }}
                                    @if($relatedRecord->access_type === 'check_in' && !$relatedRecord->checkOut())
                                        <span class="badge bg-warning">Pendiente de salida</span>
                                    @endif
                                </h5>
                                <p class="mb-0">
                                    <i class="fas fa-{{ $relatedRecord->access_type === 'check_in' ? 'sign-in-alt text-success' : 'sign-out-alt text-danger' }}"></i>
                                    {{ $relatedRecord->access_type === 'check_in' ? 'Entrada' : 'Salida' }}
                                    
                                    @if($relatedRecord->notes)
                                        <br>
                                        <small class="text-muted">
                                            <i class="far fa-sticky-note"></i> {{ $relatedRecord->notes }}
                                        </small>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('admin.check-ins.show', $relatedRecord) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                @if($relatedRecord->access_type === 'check_in' && !$relatedRecord->checkOut())
                                    <form action="{{ route('admin.check-ins.check-out', $relatedRecord) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning"
                                            onclick="return confirm('¿Registrar salida para {{ $relatedRecord->member->full_name }}?')">
                                            <i class="fas fa-sign-out-alt"></i> Registrar Salida
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@push('css')
    <style>
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: 0.25rem;
            background-color: #fff;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: 0.5rem;
            position: relative;
        }
        .info-box .info-box-icon {
            border-radius: 0.25rem;
            -ms-flex-align: center;
            align-items: center;
            display: flex;
            font-size: 1.875rem;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
            width: 70px;
            color: white;
        }
        .info-box .info-box-content {
            display: flex;
            flex-direction: column;
            -ms-flex-pack: center;
            justify-content: center;
            line-height: 1.8;
            padding: 0 10px;
            flex: 1;
        }
        .info-box .info-box-text, .info-box .progress-description {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .info-box .info-box-number {
            display: block;
            margin-top: 0.25rem;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .bg-purple {
            background-color: #6f42c1 !important;
        }
    </style>
@endpush
