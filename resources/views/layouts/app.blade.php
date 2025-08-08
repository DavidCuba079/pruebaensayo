<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Panel')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    @stack('styles')
    <style>
        /* Estilos generales */
        body {
            padding-top: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        /* Barra de navegación superior */
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            height: 60px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background-color: #4361ee !important;
        }
        
        /* Menú lateral */
        .sidebar {
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            background-color: #fff;
            box-shadow: 1px 0 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: #4a5568;
            border-radius: 0.375rem;
            margin: 0 0.5rem;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover {
            background-color: #f1f5f9;
            color: #1e40af;
        }
        
        .sidebar .nav-link.active {
            background-color: #e0f2fe;
            color: #1d4ed8;
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        /* Contenido principal */
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            transition: all 0.3s;
            min-height: calc(100vh - 60px);
        }
        
        /* Ajustes para pantallas pequeñas */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            body.sidebar-show {
                overflow: hidden;
            }
        }
        
        /* Scrollbar personalizada */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-light">
    @auth
        @include('layouts.navigation')
    @endauth

    <div class="container-fluid">
        <div class="row">
            @auth
                <!-- Sidebar -->
                <div class="sidebar d-none d-lg-block bg-white shadow-sm" style="width: 250px;">
                    <div class="d-flex flex-column h-100">
                        <!-- Logo -->
                        <div class="p-3 border-bottom text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-activity text-primary"></i>
                                </div>
                                <span class="fw-bold text-primary">GYM SYSTEM</span>
                            </div>
                        </div>
                        
                        <!-- Menú -->
                        <div class="flex-grow-1 overflow-auto py-3">
                            <ul class="nav flex-column">
                                <!-- Dashboard -->
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('admin.dashboard') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-3"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                
                                <li class="border-bottom my-2"></li>
                                
                                @can('ver_socios')
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('admin.members.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('admin.members.index') }}">
                                        <i class="bi bi-people me-3"></i>
                                        <span>Socios</span>
                                        <span class="badge bg-primary rounded-pill ms-auto">0</span>
                                    </a>
                                </li>
                                @endcan
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('classes.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('classes.index') }}">
                                        <i class="bi bi-calendar-check me-3"></i>
                                        <span>Clases</span>
                                        <span class="badge bg-primary rounded-pill ms-auto">0</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('payments.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('payments.index') }}">
                                        <i class="bi bi-credit-card me-3"></i>
                                        <span>Pagos</span>
                                        <span class="badge bg-primary rounded-pill ms-auto">0</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('reports.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('reports.index') }}">
                                        <i class="bi bi-graph-up me-3"></i>
                                        <span>Reportes</span>
                                    </a>
                                </li>
                                
                                @role('Administrador')
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('admin.roles.*') ? 'active bg-primary bg-opacity-10 text-primary fw-bold' : 'text-dark' }}" 
                                       href="{{ route('admin.roles.index') }}">
                                        <i class="bi bi-shield-lock me-3"></i>
                                        <span>Roles y Permisos</span>
                                    </a>
                                </li>
                                @endrole
                            </ul>
                            
                            <!-- Sección de configuración -->
                            <div class="px-4 py-3 mt-auto">
                                <h6 class="text-uppercase small fw-bold text-muted mb-3">Configuración</h6>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('profile.*') ? 'text-primary fw-bold' : 'text-dark' }}" 
                                           href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person me-3"></i>
                                            <span>Mi perfil</span>
                                        </a>
                                    </li>
                                    
                                    @can('ver_usuarios')
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('admin.roles.*') ? 'text-primary fw-bold' : 'text-dark' }}" 
                                           href="{{ route('admin.roles.index') }}">
                                            <i class="bi bi-person-badge me-3"></i>
                                            <span>Roles</span>
                                        </a>
                                    </li>
                                    @endcan
                                    
                                    @can('ver_permisos')
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center py-2 px-0 {{ request()->routeIs('admin.permissions.*') ? 'text-primary fw-bold' : 'text-dark' }}" 
                                           href="{{ route('admin.permissions.index') }}">
                                            <i class="bi bi-key me-3"></i>
                                            <span>Permisos</span>
                                        </a>
                                    </li>
                                    @endcan
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center py-2 px-0 text-dark" href="#">
                                            <i class="bi bi-gear me-3"></i>
                                            <span>Configuración</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido principal -->
                <div class="main-content">
                    @yield('content')
                </div>
            @else
                <div class="col-12">
                    @yield('content')
                </div>
            @endauth
        </div>
    </div>

    @stack('modals')

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    
    @if(app()->environment('local') && auth()->check())
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="text-muted">Debug Info</h6>
            <ul class="list-unstyled small">
                <li>Usuario: {{ auth()->user()->name }}</li>
                <li>Email: {{ auth()->user()->email }}</li>
                <li>ID: {{ auth()->id() }}</li>
            </ul>
        </div>
    @endif
    
    <!-- Scripts de Bootstrap y otros -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @stack('scripts')
</body>
</html>
