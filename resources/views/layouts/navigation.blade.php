<!-- Barra de navegación superior -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
    <div class="container-fluid px-3">
        <!-- Botón para mostrar/ocultar el sidebar en móviles -->
        <button class="btn btn-link text-white p-0 me-3 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                <i class="bi bi-activity text-primary"></i>
            </div>
            <span class="fw-bold d-none d-sm-inline">GYM SYSTEM</span>
        </a>

        <!-- Menú de usuario -->
        <div class="ms-auto d-flex align-items-center">
            <!-- Búsqueda (solo visible en desktop) -->
            <div class="d-none d-lg-block me-3">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control bg-white bg-opacity-10 border-0 text-white" placeholder="Buscar..." aria-label="Buscar">
                    <button class="btn btn-outline-light" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="dropdown me-2 me-lg-3">
                <button class="btn btn-link text-white text-decoration-none position-relative p-1" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.2rem 0.35rem;">
                        3
                        <span class="visually-hidden">notificaciones no leídas</span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 280px;" aria-labelledby="notificationsDropdown">
                    <li class="px-3 py-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Notificaciones</h6>
                            <span class="badge bg-primary rounded-pill">3 nuevas</span>
                        </div>
                    </li>
                    <li class="notification-item">
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <div class="flex-shrink-0 me-2">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-person-plus text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">Nuevo miembro registrado</div>
                                <div class="text-muted small">Hace 5 min</div>
                            </div>
                        </a>
                    </li>
                    <li class="notification-item">
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <div class="flex-shrink-0 me-2">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">Pago vencido - Juan Pérez</div>
                                <div class="text-muted small">Hace 1 hora</div>
                            </div>
                        </a>
                    </li>
                    <li class="notification-item">
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <div class="flex-shrink-0 me-2">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-calendar-check text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">Clase de Spinning llena</div>
                                <div class="text-muted small">Hoy, 10:30 AM</div>
                            </div>
                        </a>
                    </li>
                    <li class="text-center py-2 border-top">
                        <a href="#" class="text-decoration-none small">Ver todas las notificaciones</a>
                    </li>
                </ul>
            </div>

            <!-- Menú de usuario -->
            <div class="dropdown">
                <button class="btn p-0 d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-2 d-none d-sm-block text-end">
                            <div class="fw-medium text-white">{{ Auth::user()->name }}</div>
                            <div class="small text-white-50">
                                @if(Auth::user()->hasRole('admin'))
                                    Administrador
                                @elseif(Auth::user()->hasRole('instructor'))
                                    Instructor
                                @else
                                    Usuario
                                @endif
                            </div>
                        </div>
                        <div class="position-relative">
                            <div class="avatar avatar-sm bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-person fs-5"></i>
                            </div>
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 10px; height: 10px;"></span>
                        </div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 240px;" aria-labelledby="userDropdown">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                        <div class="small text-muted">{{ Auth::user()->email }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i> Mi perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center py-2" href="#">
                            <i class="bi bi-gear me-2"></i> Configuración
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Barra lateral para dispositivos móviles -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarMobile" aria-labelledby="sidebarMobileLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="sidebarMobileLabel">Menú</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        <div class="p-3 bg-light border-bottom">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-person fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <div class="small text-muted">
                        @if(Auth::user()->hasRole('admin'))
                            Administrador
                        @elseif(Auth::user()->hasRole('instructor'))
                            Instructor
                        @else
                            Usuario
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-grow-1 overflow-auto">
            <div class="list-group list-group-flush">
                                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action border-0 py-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-people me-2"></i> Socios
                    <span class="badge bg-primary rounded-pill float-end">0</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-calendar-check me-2"></i> Clases
                    <span class="badge bg-primary rounded-pill float-end">0</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-credit-card me-2"></i> Pagos
                    <span class="badge bg-primary rounded-pill float-end">0</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action border-0 py-3">
                    <i class="bi bi-graph-up me-2"></i> Reportes
                </a>
            </div>
            
            <div class="p-3 border-top">
                <h6 class="fw-bold text-uppercase small text-muted mb-3">Configuración</h6>
                <a href="{{ route('profile.edit') }}" class="d-flex align-items-center text-decoration-none text-muted py-2">
                    <i class="bi bi-person me-2"></i> Mi perfil
                </a>
                
                @can('ver_usuarios')
                <a href="{{ route('admin.roles.index') }}" class="d-flex align-items-center text-decoration-none text-muted py-2">
                    <i class="bi bi-person-badge me-2"></i> Roles
                </a>
                @endcan
                
                @can('ver_permisos')
                <a href="{{ route('admin.permissions.index') }}" class="d-flex align-items-center text-decoration-none text-muted py-2">
                    <i class="bi bi-key me-2"></i> Permisos
                </a>
                @endcan
                
                <a href="#" class="d-flex align-items-center text-decoration-none text-muted py-2">
                    <i class="bi bi-gear me-2"></i> Configuración
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="w-100">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 mt-3">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
