@extends('admin.layout')

@section('title', 'Editar Registro de Acceso')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Registro de Acceso</h1>
        <div>
            <a href="{{ route('admin.check-ins.show', $checkIn) }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Volver a Detalles
            </a>
            <a href="{{ route('admin.check-ins.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Ver Todos
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Miembro</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $checkIn->member->profile_photo_url }}" 
                             alt="Foto de perfil" 
                             class="img-fluid rounded-circle" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h4 class="mt-2">{{ $checkIn->member->full_name }}</h4>
                        <p class="text-muted">
                            <i class="fas fa-id-card"></i> DNI: {{ $checkIn->member->dni }}
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Membresía:</strong> 
                        @if($checkIn->membership)
                            {{ $checkIn->membership->membershipType->name }} 
                            ({{ $checkIn->membership->start_date->format('d/m/Y') }} - 
                            {{ $checkIn->membership->end_date->format('d/m/Y') }})
                            
                            @if($checkIn->membership->isActive())
                                <span class="badge bg-success ml-2">Activa</span>
                            @elseif($checkIn->membership->isExpired())
                                <span class="badge bg-danger ml-2">Expirada</span>
                            @else
                                <span class="badge bg-secondary ml-2">Inactiva</span>
                            @endif
                        @else
                            <span class="text-danger">No se encontró membresía activa</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Historial reciente del miembro -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Historial Reciente</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.check-ins.member.history', $checkIn->member) }}" class="btn btn-tool">
                            <i class="fas fa-external-link-alt"></i> Ver Todo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCheckIns as $recent)
                                    @if($recent->id !== $checkIn->id)
                                        <tr>
                                            <td>{{ $recent->check_in_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($recent->access_type === 'check_in')
                                                    <span class="badge bg-success">Entrada</span>
                                                @else
                                                    <span class="badge bg-danger">Salida</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.check-ins.show', $recent) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No hay registros recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Registro</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.check-ins.update', $checkIn) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="check_in_at">Fecha y Hora</label>
                            <div class="input-group date" id="datetimepicker">
                                <input type="datetime-local" 
                                       class="form-control @error('check_in_at') is-invalid @enderror" 
                                       id="check_in_at" 
                                       name="check_in_at" 
                                       value="{{ old('check_in_at', $checkIn->check_in_at->format('Y-m-d\TH:i')) }}"
                                       required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                @error('check_in_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="access_type">Tipo de Acceso</label>
                            <select name="access_type" id="access_type" 
                                    class="form-control @error('access_type') is-invalid @enderror" required>
                                <option value="check_in" {{ old('access_type', $checkIn->access_type) === 'check_in' ? 'selected' : '' }}>Entrada</option>
                                <option value="check_out" {{ old('access_type', $checkIn->access_type) === 'check_out' ? 'selected' : '' }}>Salida</option>
                            </select>
                            @error('access_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notas (opcional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Ej: Ingresó con invitado o cualquier otra observación">{{ old('notes', $checkIn->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notify_member" name="notify_member">
                                <label class="custom-control-label" for="notify_member">
                                    Notificar al miembro sobre este cambio
                                </label>
                                <small class="form-text text-muted">
                                    Se enviará una notificación por correo electrónico al miembro informando sobre la modificación.
                                </small>
                            </div>
                        </div>
                        
                        <div class="form-group text-right mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('admin.check-ins.show', $checkIn) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Información de auditoría -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Información de Auditoría</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 40%;">Creado por:</th>
                                <td>{{ $checkIn->created_by_user->name ?? 'Sistema' }}</td>
                            </tr>
                            <tr>
                                <th>Fecha de creación:</th>
                                <td>{{ $checkIn->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Última actualización:</th>
                                <td>{{ $checkIn->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            @if($checkIn->updated_by_user)
                                <tr>
                                    <th>Última actualización por:</th>
                                    <td>{{ $checkIn->updated_by_user->name }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Dirección IP:</th>
                                <td>{{ $checkIn->ip_address ?? 'No disponible' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar el datetimepicker
            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                locale: 'es',
                sideBySide: true,
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                }
            });
            
            // Validación del formulario
            $('form').on('submit', function(e) {
                const checkInAt = new Date($('#check_in_at').val());
                const now = new Date();
                
                // No permitir fechas futuras
                if (checkInAt > now) {
                    e.preventDefault();
                    alert('No se puede registrar una fecha futura. Por favor, verifica la fecha y hora.');
                    return false;
                }
                
                // Confirmar antes de guardar
                return confirm('¿Estás seguro de guardar los cambios en este registro de acceso?');
            });
            
            // Mostrar/ocultar campo de motivo de notificación
            $('#notify_member').change(function() {
                if ($(this).is(':checked')) {
                    $('#notification_reason_container').removeClass('d-none');
                } else {
                    $('#notification_reason_container').addClass('d-none');
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        .custom-file-label::after {
            content: "Examinar";
        }
        .table th {
            white-space: nowrap;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.8em;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush
