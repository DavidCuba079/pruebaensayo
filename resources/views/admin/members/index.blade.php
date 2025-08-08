@extends('layouts.app')

@section('title', 'Gestión de Socios')

@push('styles')
<style>
    /* Control del menú lateral */
    .sidebar-hidden {
        transform: translateX(-100%) !important;
        transition: transform 0.3s ease;
    }
    
    .main-content-expanded {
        margin-left: 0 !important;
        transition: margin-left 0.3s ease;
    }
    
    .toggle-sidebar-btn {
        position: fixed;
        top: 70px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1040;
        background: #4361ee;
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
    
    .toggle-sidebar-btn:hover {
        background: #3651d4;
        transform: translateX(-50%) scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    /* Compactación global de la tabla */
    .members-table-compact {
        font-size: 0.8rem;
    }
    
    .members-table-compact .table th,
    .members-table-compact .table td {
        padding: 0.4rem 0.6rem;
        vertical-align: middle;
        line-height: 1.2;
    }
    
    .members-table-compact .table th {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .members-table-compact .avatar-sm {
        width: 28px;
        height: 28px;
    }
    
    .members-table-compact .badge {
        font-size: 0.65rem;
        padding: 0.2em 0.4em;
    }
    
    .members-table-compact .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
    
    .members-table-compact .membership-card {
        padding: 0.4rem;
        font-size: 0.75rem;
        max-width: 180px;
    }
    
    .members-table-compact .member-info {
        font-size: 0.8rem;
    }
    
    .members-table-compact .member-info .text-muted {
        font-size: 0.7rem;
    }
    
    /* Efectos visuales mantenidos pero optimizados */
    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .membership-card {
        transition: all 0.3s ease;
    }
    
    .membership-card:hover {
        transform: translateY(-2px);
    }
    
    .status-badge {
        transition: all 0.2s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .member-info-card {
        transition: all 0.2s ease;
    }
    
    .member-info-card:hover {
        transform: translateX(5px);
    }
    
    .action-btn {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .action-btn:hover::before {
        left: 100%;
    }
    
    .members-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .members-table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: scale(1.01);
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-3px);
        }
    }
    
    /* Efecto de carga */
    .loading-spinner {
        display: inline-block;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Mejoras para la información del socio */
    .member-info-card {
        transition: all 0.3s ease;
    }
    
    .member-info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Gradientes para avatares */
    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Animación para badges de estado */
    .status-badge {
        position: relative;
        overflow: hidden;
    }
    
    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.7s;
    }
    
    .status-badge:hover::before {
        left: 100%;
    }
    
    /* Mejoras para tarjetas de membresía */
    .membership-card {
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .membership-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        pointer-events: none;
    }
    
    /* Efectos para iconos */
    .icon-bounce {
        transition: transform 0.3s ease;
    }
    
    .icon-bounce:hover {
        transform: scale(1.2) rotate(5deg);
    }
    
    /* Mejoras responsivas adicionales */
    @media (max-width: 640px) {
        .member-info-card {
            padding: 0.75rem;
        }
        
        .membership-card {
            padding: 0.5rem;
        }
        
        .status-badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Socios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Socios</li>
    </ol>

    <!-- Cuadros resumen -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Socios</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $members->total() ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Socios Activos</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $members->where('status', true)->count() ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Con Membresía</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $members->whereNotNull('activeMembership')->count() ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-award fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Nuevos Este Mes</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $members->where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para ocultar/mostrar menú lateral -->
    <button type="button" class="toggle-sidebar-btn" id="toggleSidebarBtn" title="Ocultar/Mostrar Menú">
        <i class="bi bi-list" id="toggleSidebarIcon"></i>
    </button>

    <!-- Encabezado con acciones -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.members.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Socio
            </a>
            <button type="button" class="btn btn-outline-secondary" id="exportBtn">
                <i class="bi bi-download me-1"></i> Exportar
            </button>
            <button type="button" class="btn btn-outline-info" id="printBtn">
                <i class="bi bi-printer me-1"></i> Imprimir
            </button>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-search me-1"></i> Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form action="{{ route('admin.members.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Buscar por nombre, apellido o CI..." class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" onchange="this.form.submit()" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Buscar
                        </button>
                        @if(request()->has('search') || request()->has('status'))
                            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-1"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Socios -->
    <div class="card members-table-compact">
        <div class="card-header">
            <i class="bi bi-people me-1"></i> Lista de Socios
        </div>
        @if($members->count() > 0)
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 45%;">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'first_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-white text-decoration-none">
                                        <i class="bi bi-person-badge me-1"></i>
                                        Socio
                                        @if(request('sort') === 'first_name')
                                            <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}-short ms-1"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th style="width: 35%;">
                                    <i class="bi bi-credit-card me-1"></i>
                                    Estado & Membresía
                                </th>
                                <th class="text-center" style="width: 20%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center member-info">
                                            <div class="me-2">
                                                @if($member->profile_photo_path && file_exists(storage_path('app/public/' . $member->profile_photo_path)))
                                                    <img class="rounded-circle avatar-sm" 
                                                         src="{{ asset('storage/' . $member->profile_photo_path) }}" 
                                                         alt="{{ $member->first_name }} {{ $member->last_name }}" 
                                                         style="object-fit: cover; width: 28px; height: 28px;"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center avatar-sm" style="display: none;">
                                                        <i class="bi bi-person text-white" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                @else
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center avatar-sm" style="width: 28px; height: 28px;">
                                                        <i class="bi bi-person text-white" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ Str::limit($member->first_name . ' ' . $member->last_name, 20) }}</div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-envelope me-1"></i>{{ Str::limit($member->email ?? 'Sin correo', 25) }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-credit-card me-1"></i>{{ $member->dni }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            @if($member->status)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Activo
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Inactivo
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($member->activeMembership)
                                            <div class="border rounded membership-card" style="border-color: #0d6efd !important;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="fw-bold text-primary text-truncate me-1">
                                                        <i class="bi bi-award me-1"></i>
                                                        {{ Str::limit($member->activeMembership->membershipType->name ?? 'N/A', 12) }}
                                                    </small>
                                                    @if($member->activeMembership->days_remaining > 0)
                                                        <span class="badge {{ $member->activeMembership->days_remaining <= 7 ? 'bg-warning' : 'bg-success' }}">
                                                            {{ $member->activeMembership->days_remaining }}d
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">Vencida</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $member->activeMembership->end_date->format('d/m/y') }}
                                                </small>
                                            </div>
                                        @else
                                            <div class="border rounded membership-card" style="border-color: #ffc107 !important;">
                                                <small class="text-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Sin membresía
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.members.edit', $member) }}" 
                                               class="btn btn-success btn-sm" title="Editar Socio">
                                                <i class="bi bi-pencil-square me-1"></i>Editar
                                            </a>
                                            <form action="{{ route('admin.members.destroy', $member) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirmDeactivate('{{ $member->first_name }} {{ $member->last_name }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-warning btn-sm" title="Desactivar Socio">
                                                    <i class="bi bi-person-dash me-1"></i>Desactivar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            
            <!-- Paginación -->
            <div class="card-footer">
                {{ $members->withQueryString()->links() }}
            </div>
        @else
            <div class="card-body text-center py-5">
                <i class="bi bi-people display-1 text-muted mb-3"></i>
                <h3 class="h5">No se encontraron socios</h3>
                <p class="text-muted">
                    @if(request()->has('search') || request()->has('status'))
                        Intenta con otros criterios de búsqueda o <a href="{{ route('admin.members.index') }}" class="text-decoration-none">ver todos los socios</a>.
                    @else
                        Aún no hay socios registrados. <a href="{{ route('admin.members.create') }}" class="text-decoration-none">Agrega uno nuevo</a>.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Funcionalidad de exportar a Excel
    document.getElementById('exportBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Mostrar indicador de carga
        const exportBtn = this;
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-1"></i> Exportando...';
        exportBtn.disabled = true;
        
        // Realizar la petición de exportación
        fetch("{{ route('admin.members.export') }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la exportación');
                }
                return response.blob();
            })
            .then(blob => {
                // Crear un enlace temporal para la descarga
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `socios_${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Mostrar notificación de éxito
                showNotification('success', 'Exportación completada', 'Los datos de los socios se han exportado correctamente.');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', 'No se pudo completar la exportación. Por favor, inténtelo de nuevo.');
            })
            .finally(() => {
                // Restaurar el botón
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            });
    });
    
    // Función para mostrar notificaciones
    function showNotification(type, title, message) {
        // Aquí podrías integrar con tu sistema de notificaciones
        // Por ahora usamos un alert simple
        alert(`${title}: ${message}`);
    }

    // Funcionalidad de imprimir
    document.getElementById('printBtn').addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });

    // Función para confirmar desactivación
    function confirmDeactivate(memberName) {
        return confirm(`¿Está seguro de desactivar al socio "${memberName}"?\n\nEl socio será marcado como inactivo y:\n• No aparecerá en la lista de socios activos\n• Se mantendrán todos sus datos y registros\n• Podrá ser reactivado posteriormente si es necesario\n\n¿Desea continuar?`);
    }

    // Funcionalidad para ocultar/mostrar menú lateral
    document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const toggleIcon = document.getElementById('toggleSidebarIcon');
        
        // Alternar clases
        sidebar.classList.toggle('sidebar-hidden');
        mainContent.classList.toggle('main-content-expanded');
        toggleBtn.classList.toggle('sidebar-hidden');
        
        // Cambiar icono
        if (sidebar.classList.contains('sidebar-hidden')) {
            toggleIcon.className = 'bi bi-arrow-right';
            toggleBtn.title = 'Mostrar Menú';
        } else {
            toggleIcon.className = 'bi bi-list';
            toggleBtn.title = 'Ocultar Menú';
        }
    });

    // Scripts específicos para esta vista
    document.addEventListener('DOMContentLoaded', function() {
        // Ocultar el menú por defecto al cargar la página
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const toggleIcon = document.getElementById('toggleSidebarIcon');
        
        // Aplicar clases para ocultar el menú inicialmente
        sidebar.classList.add('sidebar-hidden');
        mainContent.classList.add('main-content-expanded');
        toggleBtn.classList.add('sidebar-hidden');
        toggleIcon.className = 'bi bi-arrow-right';
        toggleBtn.title = 'Mostrar Menú';
        
        // Animación de carga para los botones de acción
        const actionButtons = document.querySelectorAll('a[href*="members"], button[type="submit"]');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Solo para botones que no sean de eliminación
                if (!this.classList.contains('btn-outline-danger')) {
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Cargando...';
                    
                    // Restaurar después de un breve momento (para navegación)
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                    }, 1000);
                }
            });
        });
    });
</script>
@endpush

@endsection
