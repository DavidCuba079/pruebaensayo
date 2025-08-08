<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GymPro System') }} - @yield('title')</title>
    
    <!-- Fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            margin-left: -16rem;
        }
        .main-content {
            transition: all 0.3s;
        }
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-white w-64 fixed h-full overflow-y-auto">
            <div class="p-4">
                <h1 class="text-2xl font-bold">GymPro</h1>
                <p class="text-gray-400 text-sm">Sistema de Gestión</p>
            </div>
            <nav class="mt-6">
                <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider">
                    Principal
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="bi bi-speedometer2 mr-3"></i>
                    Dashboard
                </a>
                
                <!-- Menú de Administración -->
                <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider mt-4">
                    Administración
                </div>
                
                <!-- Roles -->
                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-700' : '' }}">
                    <i class="bi bi-shield-lock mr-3"></i>
                    Roles
                </a>
                
                <!-- Permisos -->
                <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-700' : '' }}">
                    <i class="bi bi-key mr-3"></i>
                    Permisos
                </a>
                </a>
                <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider mt-4">
                    Gestión
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-white bg-gray-700">
                    <i class="bi bi-people mr-3"></i>
                    Usuarios
                </a>
                <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 {{ request()->routeIs('admin.members.*') ? 'bg-gray-700 text-white' : '' }}">
                    <i class="bi bi-person-badge mr-3"></i>
                    Socios
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">
                    <i class="bi bi-credit-card mr-3"></i>
                    Membresías
                </a>
                <div class="px-4 py-2 text-gray-400 text-xs font-semibold uppercase tracking-wider mt-4">
                    Configuración
                </div>
                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700">
                    <i class="bi bi-gear mr-3"></i>
                    Ajustes
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="main-content flex-1 flex flex-col ml-64">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <h1 class="ml-4 text-xl font-semibold text-gray-800">@yield('title')</h1>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center focus:outline-none">
                                <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down ml-1 text-gray-500"></i>
                            </button>
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-person mr-2"></i> Perfil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-gear mr-2"></i> Configuración
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-box-arrow-right mr-2"></i> Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class="bi bi-check-circle-fill text-green-500 mr-3"></i>
                            </div>
                            <div>
                                <p class="font-bold">¡Éxito!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3"></i>
                            </div>
                            <div>
                                <p class="font-bold">¡Error!</p>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Toggle user dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            
            if (!userMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
