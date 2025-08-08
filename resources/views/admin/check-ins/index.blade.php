@extends('admin.layout')

@section('title', 'Registros de Acceso')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registros de Acceso</h1>
        <div>
            <a href="{{ route('admin.check-ins.dashboard') }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-tachometer-alt"></i> Panel de Control
            </a>
            <a href="{{ route('admin.check-ins.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Entrada/Salida
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar Registros</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.check-ins.index') }}" method="GET" class="mb-0">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Buscar por nombre o DNI</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="Nombre, apellido o DNI">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="access_type">Tipo de Acceso</label>
                            <select name="access_type" id="access_type" class="form-control">
                                <option value="">Todos</option>
                                <option value="check_in" {{ request('access_type') === 'check_in' ? 'selected' : '' }}>Entrada</option>
                                <option value="check_out" {{ request('access_type') === 'check_out' ? 'selected' : '' }}>Salida</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">Desde</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">Hasta</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            @if(request()->hasAny(['search', 'access_type', 'date_from', 'date_to']))
                                <a href="{{ route('admin.check-ins.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Miembro</th>
                            <th>DNI</th>
                            <th>Tipo</th>
                            <th>Membresía</th>
                            <th>Registrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkIns as $checkIn)
                            <tr>
                                <td>
                                    {{ $checkIn->check_in_at->format('d/m/Y H:i') }}
                                    @if($checkIn->created_at->diffInHours() < 24)
                                        <span class="badge bg-success">Reciente</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.members.show', $checkIn->member_id) }}" class="text-dark">
                                        {{ $checkIn->member->full_name }}
                                    </a>
                                </td>
                                <td>{{ $checkIn->member->dni }}</td>
                                <td>
                                    @if($checkIn->access_type === 'check_in')
                                        <span class="badge bg-success">
                                            <i class="fas fa-sign-in-alt"></i> Entrada
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-sign-out-alt"></i> Salida
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($checkIn->membership)
                                        <a href="{{ route('admin.memberships.show', $checkIn->membership_id) }}" class="text-dark">
                                            {{ $checkIn->membership->membershipType->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $checkIn->membership->start_date->format('d/m/Y') }} - 
                                            {{ $checkIn->membership->end_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>
                                <td>{{ $checkIn->user->name ?? 'Sistema' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.check-ins.show', $checkIn) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($checkIn->access_type === 'check_in' && !$checkIn->checkOut())
                                            <form action="{{ route('admin.check-ins.check-out', $checkIn) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                    onclick="return confirm('¿Registrar salida para {{ $checkIn->member->full_name }}?')">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.check-ins.destroy', $checkIn) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No se encontraron registros de acceso.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($checkIns->hasPages())
            <div class="card-footer clearfix">
                {{ $checkIns->withQueryString()->links() }}
            </div>
        @endif
    </div>
@stop

@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar datepickers
            $('input[type="date"]').attr('max', new Date().toISOString().split('T')[0]);
            
            // Si se selecciona una fecha de inicio, establecer la fecha mínima de fin
            $('input[name="date_from"]').on('change', function() {
                $('input[name="date_to"]').attr('min', $(this).val());
            });
            
            // Si se selecciona una fecha de fin, establecer la fecha máxima de inicio
            $('input[name="date_to"]').on('change', function() {
                $('input[name="date_from"]').attr('max', $(this).val());
            });
        });
    </script>
@endpush
