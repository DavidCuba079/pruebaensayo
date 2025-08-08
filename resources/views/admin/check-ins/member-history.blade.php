@extends('admin.layout')

@section('title', 'Historial de Accesos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Historial de Accesos</h1>
        <div>
            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Volver al Perfil
            </a>
            <a href="{{ route('admin.check-ins.create') }}?member_id={{ $member->id }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Acceso
            </a>
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
                    <img src="{{ $member->profile_photo_url }}" 
                         alt="Foto de perfil" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                    
                    <h4>{{ $member->full_name }}</h4>
                    <p class="text-muted">
                        <i class="fas fa-id-card"></i> DNI: {{ $member->dni }}
                    </p>
                    
                    <div class="mt-3">
                        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas de acceso -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $checkIns->total() }}</h3>
                            <p>Registros Totales</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                    
                    @php
                        $lastMonth = now()->subMonth();
                        $lastMonthCount = $member->checkIns()->where('check_in_at', '>=', $lastMonth)->count();
                        $previousMonthCount = $member->checkIns()
                            ->whereBetween('check_in_at', [now()->subMonths(2), $lastMonth])
                            ->count();
                            
                        $change = $previousMonthCount > 0 
                            ? round((($lastMonthCount - $previousMonthCount) / $previousMonthCount) * 100) 
                            : 100;
                        $isIncrease = $change >= 0;
                    @endphp
                    
                    <div class="small-box bg-{{ $isIncrease ? 'success' : 'danger' }}">
                        <div class="inner">
                            <h3>{{ $lastMonthCount }}</h3>
                            <p>Últimos 30 días</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="small-box-footer">
                            @if($previousMonthCount > 0)
                                {{ abs($change) }}% {{ $isIncrease ? '↑' : '↓' }} respecto al mes anterior
                            @else
                                Sin datos del mes anterior
                            @endif
                        </div>
                    </div>
                    
                    <!-- Promedio de visitas por semana -->
                    @php
                        $lastThreeMonths = $member->checkIns()
                            ->where('check_in_at', '>=', now()->subMonths(3))
                            ->get()
                            ->groupBy(function($date) {
                                return \Carbon\Carbon::parse($date->check_in_at)->format('Y-W');
                            });
                            
                        $weeksWithVisits = $lastThreeMonths->count();
                        $totalVisits = $lastThreeMonths->sum(function($visits) {
                            return $visits->count();
                        });
                        $avgVisitsPerWeek = $weeksWithVisits > 0 ? round($totalVisits / $weeksWithVisits, 1) : 0;
                    @endphp
                    
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $avgVisitsPerWeek }}</h3>
                            <p>Visitas por semana (promedio)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="small-box-footer">
                            Basado en los últimos 3 meses
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Membresía activa -->
            @if($member->activeMembership)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Membresía Activa</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h4>{{ $member->activeMembership->membershipType->name }}</h4>
                            <p class="mb-1">
                                {{ $member->activeMembership->start_date->format('d/m/Y') }} - 
                                {{ $member->activeMembership->end_date->format('d/m/Y') }}
                            </p>
                            <p class="mb-3">
                                @if($member->activeMembership->isActive())
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </span>
                                @elseif($member->activeMembership->isExpired())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-circle"></i> Expirada
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @endif
                            </p>
                            <a href="{{ route('admin.memberships.show', $member->activeMembership) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-8">
            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtrar Historial</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.check-ins.member.history', $member) }}" method="GET" class="mb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="access_type">Tipo de Acceso</label>
                                    <select name="access_type" id="access_type" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="check_in" {{ request('access_type') === 'check_in' ? 'selected' : '' }}>Entrada</option>
                                        <option value="check_out" {{ request('access_type') === 'check_out' ? 'selected' : '' }}>Salida</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_from">Desde</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" 
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_to">Hasta</label>
                                    <div class="input-group">
                                        <input type="date" name="date_to" id="date_to" class="form-control" 
                                               value="{{ request('date_to') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i> Filtrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(request()->hasAny(['access_type', 'date_from', 'date_to']))
                            <div class="text-right">
                                <a href="{{ route('admin.check-ins.member.history', $member) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Limpiar Filtros
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            
            <!-- Gráfico de visitas por día -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Frecuencia de Visitas (Últimos 30 días)</h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="visitsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Lista de accesos -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Registros de Acceso</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $checkIns->total() }} registros</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Tipo</th>
                                    <th>Duración</th>
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
                                            @if($checkIn->access_type === 'check_in' && $checkIn->checkOut())
                                                {{ $checkIn->getFormattedDuration() }}
                                            @elseif($checkIn->access_type === 'check_out' && $checkIn->checkIn())
                                                {{ $checkIn->getFormattedDuration() }}
                                            @elseif($checkIn->access_type === 'check_in')
                                                <span class="text-warning">En progreso</span>
                                            @else
                                                <span class="text-muted">N/A</span>
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
                                                        <i class="fas fa-sign-out-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="mb-0">No se encontraron registros de acceso</p>
                                        </td>
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
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 0 5px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box p {
            font-size: 0.9rem;
            margin: 0;
        }
        .small-box .icon {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 0;
            font-size: 60px;
            color: rgba(0,0,0,0.15);
            transition: all .3s linear;
        }
        .small-box .small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            display: block;
            font-size: 0.8rem;
        }
        .small-box:hover .icon {
            font-size: 65px;
        }
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
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
            
            // Gráfico de visitas
            const ctx = document.getElementById('visitsChart').getContext('2d');
            
            // Datos para el gráfico (simulados, deberías reemplazarlos con datos reales)
            const labels = [];
            const data = [];
            
            // Generar etiquetas para los últimos 30 días
            for (let i = 30; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' }));
                
                // Simular datos aleatorios (reemplazar con datos reales)
                data.push(Math.floor(Math.random() * 5));
            }
            
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visitas',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
