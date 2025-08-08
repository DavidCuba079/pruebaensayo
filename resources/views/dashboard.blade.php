
@extends('layouts.app')

@section('title', 'Panel de Control')

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
    
    /* Estilos para el dashboard */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        transition: margin-left 0.3s;
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
<!-- Botón para ocultar/mostrar menú lateral -->
<button type="button" class="toggle-sidebar-btn" id="toggleSidebarBtn" title="Ocultar/Mostrar Menú">
    <i class="bi bi-list" id="toggleSidebarIcon"></i>
</button>

<div class="main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel de Control</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Compartir</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Exportar</button>
            </div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#quickActionModal">
                <i class="bi bi-plus-lg me-1"></i> Acción Rápida
            </button>
        </div>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Socios Activos</h6>
                            <h2 class="mb-0">245</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-white text-primary">+12% este mes</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Ingresos Mensuales</h6>
                            <h2 class="mb-0">$12,450</h2>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-white text-success">+8% este mes</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Clases Hoy</h6>
                            <h2 class="mb-0">5</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-white text-warning">2 en curso</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Membresías por Vencer</h6>
                            <h2 class="mb-0">18</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <a href="#" class="text-white">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Ingresos -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Ingresos Mensuales</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Distribución de Membresías</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="chart-container" style="position: relative; width: 100%; height: 250px;">
                        <canvas id="membershipChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Últimos Miembros -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Últimos Miembros Registrados</h6>
            <a href="#" class="btn btn-sm btn-outline-primary">Ver todos</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Membresía</th>
                            <th>Fecha de Inicio</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>Premium Anual</td>
                            <td>01/08/2023</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>María Gómez</td>
                            <td>Básica Mensual</td>
                            <td>30/07/2023</td>
                            <td><span class="badge bg-warning">Por vencer</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Carlos López</td>
                            <td>Intermedia Trimestral</td>
                            <td>28/07/2023</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de acción rápida -->
<div class="modal fade" id="quickActionModal" tabindex="-1" aria-labelledby="quickActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickActionModalLabel">Acción Rápida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="bi bi-person-plus fs-1 mb-2"></i>
                            <span>Nuevo Socio</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="bi bi-cash-coin fs-1 mb-2"></i>
                            <span>Registrar Pago</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="bi bi-calendar-plus fs-1 mb-2"></i>
                            <span>Nueva Clase</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                            <i class="bi bi-bell fs-1 mb-2"></i>
                            <span>Recordatorios</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Variable global para almacenar las instancias de los gráficos
    let revenueChart = null;
    let membershipChart = null;

    // Función para inicializar el gráfico de ingresos
    function initRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return false;
        
        try {
            // Destruir gráfico existente si existe
            if (revenueChart) {
                revenueChart.destroy();
            }
            
            // Verificar si el contexto 2D está disponible
            const ctx2d = ctx.getContext('2d');
            if (!ctx2d) return false;
            
            // Crear nueva instancia del gráfico
            revenueChart = new Chart(ctx2d, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos 2023',
                        data: [18500, 20100, 19800, 21500, 23000, 24500, 26000, 25500, 27000, 28500, 30000, 31500],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4361ee',
                        pointBorderWidth: 2,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#4361ee',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return ' $' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            
            return true;
        } catch (error) {
            console.error('Error al inicializar el gráfico de ingresos:', error);
            return false;
        }
    }

    // Función para inicializar el gráfico de membresías
    function initMembershipChart() {
        const ctx = document.getElementById('membershipChart');
        if (!ctx) return false;
        
        try {
            // Destruir gráfico existente si existe
            if (membershipChart) {
                membershipChart.destroy();
            }
            
            // Verificar si el contexto 2D está disponible
            const ctx2d = ctx.getContext('2d');
            if (!ctx2d) return false;
            
            // Crear nueva instancia del gráfico
            membershipChart = new Chart(ctx2d, {
                type: 'doughnut',
                data: {
                    labels: ['Básica', 'Intermedia', 'Premium', 'Familiar'],
                    datasets: [{
                        data: [35, 25, 30, 10],
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e'
                        ],
                        hoverBackgroundColor: [
                            '#2e59d9',
                            '#17a673',
                            '#2c9faf',
                            '#dda20a'
                        ],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
            
            return true;
        } catch (error) {
            console.error('Error al inicializar el gráfico de membresías:', error);
            return false;
        }
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

    // Inicializar los gráficos cuando el DOM esté listo
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
        
        // Inicializar los gráficos
        initRevenueChart();
        initMembershipChart();
        
        // Volver a inicializar los gráficos si se redimensiona la ventana
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                initRevenueChart();
                initMembershipChart();
            }, 250);
        });
    });
</script>
@endpush