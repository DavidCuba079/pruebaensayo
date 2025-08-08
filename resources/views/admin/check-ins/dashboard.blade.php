@extends('admin.layout')

@section('title', 'Panel de Control de Acceso')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Panel de Control de Acceso</h1>
        <a href="{{ route('admin.check-ins.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Entrada/Salida
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Tarjeta de estadísticas: Entradas de hoy -->
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $todayCount }}</h3>
                    <p>Entradas Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <a href="{{ route('admin.check-ins.index', ['date_from' => today()->format('Y-m-d')]) }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Tarjeta de estadísticas: Miembros activos -->
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeMembers }}</h3>
                    <p>Miembros Activos Ahora</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.check-ins.index', ['access_type' => 'check_in', 'active' => 1]) }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Tarjeta de estadísticas: Membresías activas -->
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $activeMemberships }}</h3>
                    <p>Membresías Activas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <a href="{{ route('admin.memberships.index', ['status' => 'active']) }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Tarjeta de acciones rápidas -->
        <div class="col-md-3">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>Acciones</h3>
                    <p>Gestión Rápida</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="p-2">
                    <a href="{{ route('admin.members.create') }}" class="btn btn-block btn-sm btn-outline-light mb-2">
                        <i class="fas fa-user-plus"></i> Nuevo Socio
                    </a>
                    <a href="{{ route('admin.memberships.create') }}" class="btn btn-block btn-sm btn-outline-light">
                        <i class="fas fa-id-card"></i> Nueva Membresía
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos registros de acceso -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Últimos Registros de Acceso</h3>
            <div class="card-tools">
                <a href="{{ route('admin.check-ins.index') }}" class="btn btn-tool">
                    <i class="fas fa-list"></i> Ver Todos
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Miembro</th>
                            <th>Tipo</th>
                            <th>Registrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCheckIns as $checkIn)
                            <tr>
                                <td>
                                    {{ $checkIn->check_in_at->format('d/m/Y H:i') }}
                                    @if($checkIn->created_at->diffInHours() < 24)
                                        <span class="badge bg-success">Nuevo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.members.show', $checkIn->member_id) }}">
                                        {{ $checkIn->member->full_name }}
                                    </a>
                                </td>
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
                                <td>{{ $checkIn->user->name ?? 'Sistema' }}</td>
                                <td>
                                    <a href="{{ route('admin.check-ins.show', $checkIn) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($checkIn->access_type === 'check_in' && !$checkIn->checkOut())
                                        <form action="{{ route('admin.check-ins.check-out', $checkIn) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                onclick="return confirm('¿Registrar salida para {{ $checkIn->member->full_name }}?')">
                                                <i class="fas fa-sign-out-alt"></i> Salida
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay registros de acceso recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
            position: relative;
            color: #fff !important;
        }
        .small-box .inner {
            padding: 10px;
        }
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box p {
            font-size: 1rem;
            margin: 0;
        }
        .small-box .icon {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 0;
            font-size: 70px;
            color: rgba(0,0,0,0.15);
            transition: all .3s linear;
        }
        .small-box:hover .icon {
            font-size: 80px;
        }
        .small-box .small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: rgba(255,255,255,0.8);
            color: #fff;
            text-decoration: none;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            display: block;
        }
        .small-box .small-box-footer:hover {
            color: #fff;
            background: rgba(0,0,0,0.15);
        }
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        .bg-purple .btn-outline-light {
            border-color: rgba(255,255,255,0.5);
        }
        .bg-purple .btn-outline-light:hover {
            background-color: rgba(255,255,255,0.2);
            border-color: #fff;
        }
    </style>
@endpush
