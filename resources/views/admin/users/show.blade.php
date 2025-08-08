@extends('admin.layout')

@section('title', 'Detalles del Usuario: ' . $user->name)

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    <i class="bi bi-person-badge mr-2"></i> Detalles del Usuario
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Información detallada de {{ $user->name }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-pencil-fill mr-2"></i> Editar
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-arrow-left mr-2"></i> Volver al listado
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Información del Perfil -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <div class="flex flex-col items-center">
                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden mb-4 border-4 border-white shadow-md">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        
                        <!-- Rol -->
                        @if($user->role === 'admin')
                            <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                <i class="bi bi-shield-lock mr-1"></i> Administrador
                            </span>
                        @elseif($user->role === 'trainer')
                            <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="bi bi-person-badge mr-1"></i> Entrenador
                            </span>
                        @else
                            <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bi bi-person mr-1"></i> Miembro
                            </span>
                        @endif
                        
                        <!-- Estado -->
                        @if($user->status)
                            <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bi bi-check-circle mr-1"></i> Activo
                            </span>
                        @else
                            <span class="mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="bi bi-x-circle mr-1"></i> Inactivo
                            </span>
                        @endif
                        
                        <div class="mt-4 flex space-x-3">
                            <a href="mailto:{{ $user->email }}" class="text-gray-400 hover:text-blue-600" title="Enviar correo">
                                <i class="bi bi-envelope-fill text-xl"></i>
                            </a>
                            @if($user->phone)
                                <a href="tel:{{ $user->phone }}" class="text-gray-400 hover:text-green-600" title="Llamar">
                                    <i class="bi bi-telephone-fill text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Información de la cuenta -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Información de la Cuenta</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <i class="bi bi-envelope mr-2 mt-0.5 text-gray-400"></i>
                                <span class="break-all">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-calendar-check mr-2 text-gray-400"></i>
                                <span>Registrado el {{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div class="flex items-center text-green-600">
                                    <i class="bi bi-shield-check mr-2"></i>
                                    <span>Correo verificado</span>
                                </div>
                            @else
                                <div class="flex items-center text-yellow-600">
                                    <i class="bi bi-shield-exclamation mr-2"></i>
                                    <span>Correo no verificado</span>
                                </div>
                            @endif
                            <div class="flex items-center">
                                <i class="bi bi-clock-history mr-2 text-gray-400"></i>
                                <span>Última actualización: {{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            @if($user->last_login_at)
                                <div class="flex items-center">
                                    <i class="bi bi-box-arrow-in-right mr-2 text-gray-400"></i>
                                    <span>Último inicio de sesión: {{ $user->last_login_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna Derecha: Detalles -->
            <div class="md:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="bi bi-person-lines-fill mr-2"></i> Información Personal
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <!-- Nombre -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-gray-50">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-person mr-2"></i> Nombre Completo
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->name }}
                                </dd>
                            </div>
                            
                            <!-- DNI -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-white">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-card-text mr-2"></i> DNI
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->dni ?? 'No especificado' }}
                                </dd>
                            </div>
                            
                            <!-- Teléfono -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-gray-50">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-telephone mr-2"></i> Teléfono
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->phone ?? 'No especificado' }}
                                </dd>
                            </div>
                            
                            <!-- Dirección -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-white">
                                <dt class="text-sm font-medium text-gray-500 flex items-start">
                                    <i class="bi bi-geo-alt mr-2 mt-0.5"></i> Dirección
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $user->address ?? 'No especificada' }}
                                </dd>
                            </div>
                            
                            <!-- Rol -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-gray-50">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-person-badge mr-2"></i> Rol
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($user->role === 'admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="bi bi-shield-lock mr-1"></i> Administrador
                                        </span>
                                    @elseif($user->role === 'trainer')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="bi bi-person-badge mr-1"></i> Entrenador
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="bi bi-person mr-1"></i> Miembro
                                        </span>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $user->isAdmin() ? 'Acceso total al sistema' : ($user->isTrainer() ? 'Puede gestionar socios y clases' : 'Acceso limitado a funciones básicas') }}
                                    </p>
                                </dd>
                            </div>
                            
                            <!-- Estado de la Cuenta -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-white">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-shield-check mr-2"></i> Estado de la Cuenta
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($user->status)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1"></i> Activa
                                        </span>
                                        <p class="mt-1 text-xs text-gray-500">El usuario puede iniciar sesión en el sistema.</p>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="bi bi-x-circle mr-1"></i> Inactiva
                                        </span>
                                        <p class="mt-1 text-xs text-gray-500">El usuario no puede iniciar sesión en el sistema.</p>
                                    @endif
                                </dd>
                            </div>
                            
                            <!-- Historial de Actividad -->
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-gray-50">
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    <i class="bi bi-activity mr-2"></i> Actividad Reciente
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <ul class="space-y-2">
                                        <li class="flex items-center">
                                            <i class="bi bi-calendar-check text-green-500 mr-2"></i>
                                            <span>Cuenta creada el {{ $user->created_at->format('d/m/Y \a \l\a\s H:i') }}</span>
                                        </li>
                                        <li class="flex items-center">
                                            <i class="bi bi-arrow-repeat text-blue-500 mr-2"></i>
                                            <span>Última actualización: {{ $user->updated_at->diffForHumans() }}</span>
                                        </li>
                                        @if($user->last_login_at)
                                            <li class="flex items-center">
                                                <i class="bi bi-box-arrow-in-right text-purple-500 mr-2"></i>
                                                <span>Último inicio de sesión: {{ $user->last_login_at->diffForHumans() }}</span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class="bi bi-globe text-gray-500 mr-2"></i>
                                                <span>IP del último acceso: {{ $user->last_login_ip ?? 'No disponible' }}</span>
                                            </li>
                                        @else
                                            <li class="flex items-center">
                                                <i class="bi bi-question-circle text-yellow-500 mr-2"></i>
                                                <span>Nunca ha iniciado sesión</span>
                                            </li>
                                        @endif
                                    </ul>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="bi bi-lightning-charge-fill mr-2 text-yellow-500"></i> Acciones Rápidas
                        </h3>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($user->id !== auth()->id())
                                @if($user->status)
                                    <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            <i class="bi bi-pause-fill mr-2"></i> Desactivar Cuenta
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="bi bi-play-fill mr-2"></i> Activar Cuenta
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <i class="bi bi-person-check-fill mr-2"></i> Iniciar como este usuario
                                    </button>
                                </form>
                                
                                <a href="{{ route('admin.users.reset-password', $user) }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="bi bi-key-fill mr-2"></i> Restablecer Contraseña
                                </a>
                            @else
                                <div class="md:col-span-2 p-3 bg-blue-50 text-blue-700 rounded-md text-sm">
                                    <i class="bi bi-info-circle-fill mr-2"></i> No puedes realizar acciones sobre tu propia cuenta.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    dt {
        min-height: 2.5rem;
    }
</style>
@endpush
@endsection
