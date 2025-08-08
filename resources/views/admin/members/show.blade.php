@extends('layouts.app')

@section('title', 'Detalles del Socio: ' . $member->full_name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado con botón de volver -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detalles del Socio</h2>
            <p class="text-sm text-gray-600">Información detallada del socio.</p>
        </div>
        <div class="mt-4 md:mt-0 space-x-3">
            <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-100 text-yellow-800 hover:bg-yellow-200 font-medium py-2 px-4 rounded-md flex items-center">
                <i class="bi bi-pencil-square mr-2"></i> Editar
            </a>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col md:flex-row items-start md:items-center">
                <!-- Foto de perfil -->
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    @if($member->profile_photo_path)
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-sm" 
                             src="{{ Storage::url($member->profile_photo_path) }}" 
                             alt="{{ $member->full_name }}">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="bi bi-person text-gray-500 text-5xl"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Información básica -->
                <div class="flex-1">
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $member->full_name }}</h3>
                        <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full {{ $member->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $member->status ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <div class="mt-2 flex flex-wrap items-center text-sm text-gray-500">
                        <div class="flex items-center mr-4">
                            <i class="bi bi-person-vcard mr-1"></i>
                            <span>DNI: {{ $member->dni }}</span>
                        </div>
                        <div class="flex items-center mr-4">
                            <i class="bi bi-gender-ambiguous mr-1"></i>
                            <span>{{ $member->gender == 'M' ? 'Masculino' : ($member->gender == 'F' ? 'Femenino' : 'Otro') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-calendar3 mr-1"></i>
                            <span>Nacimiento: {{ $member->birth_date->format('d/m/Y') }} ({{ $member->age }} años)</span>
                        </div>
                    </div>
                    
                    <!-- Membresía activa -->
                    @if($member->activeMembership)
                    <div class="mt-3">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="bi bi-credit-card mr-1"></i>
                            <span>Membresía: {{ $member->activeMembership->membershipType->name }}</span>
                            <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-200">
                                Vence: {{ $member->activeMembership->end_date->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="bi bi-exclamation-triangle mr-1"></i>
                            Sin membresía activa
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Pestañas de información -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button type="button" id="tab-info" class="tab-button active border-b-2 border-blue-500 text-blue-600 px-4 py-4 text-sm font-medium">
                    Información Personal
                </button>
                <button type="button" id="tab-memberships" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-4 py-4 text-sm font-medium">
                    Historial de Membresías
                </button>
                <button type="button" id="tab-checkins" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-4 py-4 text-sm font-medium">
                    Asistencia
                </button>
                <button type="button" id="tab-payments" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-4 py-4 text-sm font-medium">
                    Pagos
                </button>
            </nav>
        </div>
        
        <!-- Contenido de las pestañas -->
        <div class="p-6">
            <!-- Pestaña de Información Personal -->
            <div id="tab-content-info" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Columna Izquierda -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="space-y-4">
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->email ?? 'No especificado' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->phone ?? 'No especificado' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Dirección</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $member->address ?? 'No especificada' }}
                                        @if($member->city || $member->postal_code)
                                            <br>
                                            {{ $member->city }}{{ $member->postal_code ? ', CP ' . $member->postal_code : '' }}
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Información Adicional</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="space-y-4">
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Estado Civil</dt>
                                    <dd class="text-sm text-gray-900">
                                        @switch($member->marital_status)
                                            @case('single') Soltero/a @break
                                            @case('married') Casado/a @break
                                            @case('divorced') Divorciado/a @break
                                            @case('widowed') Viudo/a @break
                                            @default No especificado
                                        @endswitch
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Ocupación</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->occupation ?? 'No especificada' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                @if($member->notes)
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Notas</dt>
                                    <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $member->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Columna Derecha -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contacto de Emergencia</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($member->emergency_contact_name || $member->emergency_contact_phone)
                            <dl class="space-y-4">
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Nombre</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->emergency_contact_name ?? 'No especificado' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->emergency_contact_phone ?? 'No especificado' }}</dd>
                                </div>
                                @if($member->emergency_contact_relationship)
                                <div class="flex">
                                    <dt class="w-1/3 text-sm font-medium text-gray-500">Parentesco</dt>
                                    <dd class="text-sm text-gray-900">{{ $member->emergency_contact_relationship }}</dd>
                                </div>
                                @endif
                            </dl>
                            @else
                            <p class="text-sm text-gray-500 italic">No se ha registrado ningún contacto de emergencia.</p>
                            @endif
                        </div>
                        
                        <!-- Membresía Activa -->
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Membresía Activa</h3>
                        
                        @if($member->activeMembership && $stats['has_active_membership'])
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600">
                                            <i class="bi bi-credit-card text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $member->activeMembership->membershipType->name ?? 'Sin tipo' }}</h4>
                                        <div class="mt-1 text-sm text-gray-600">
                                            <p>Inicio: {{ $member->activeMembership->start_date->format('d/m/Y') ?? 'N/A' }}</p>
                                            <p>Vencimiento: {{ $member->activeMembership->end_date->format('d/m/Y') ?? 'N/A' }}</p>
                                            <p class="mt-2">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $member->activeMembership->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($member->activeMembership->status ?? 'inactive') }}
                                                </span>
                                            </p>
                                        </div>
                                        
                                        <div class="mt-3 flex space-x-3">
                                            <a href="#" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                                                Ver detalles
                                            </a>
                                            @if($member->activeMembership->status == 'active')
                                            <button type="button" class="text-sm font-medium text-yellow-600 hover:text-yellow-800"
                                                onclick="document.getElementById('renew-membership-form').classList.toggle('hidden')">
                                                Renovar
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-100 text-yellow-600">
                                            <i class="bi bi-exclamation-triangle text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">Sin membresía activa</h4>
                                        <div class="mt-1 text-sm text-gray-600">
                                            <p>Este socio no tiene una membresía activa actualmente.</p>
                                            <p class="mt-2">
                                                <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    <i class="bi bi-plus-circle mr-1"></i> Crear membresía
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($member->activeMembership) && $member->activeMembership && $member->activeMembership->status == 'active')
                            <!-- Formulario de renovación (oculto por defecto) -->
                            <div id="renew-membership-form" class="mt-4 p-4 bg-white rounded-lg border border-gray-200 hidden">
                                <h5 class="text-sm font-medium text-gray-900 mb-3">Renovar Membresía</h5>
                                <form action="#" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label for="membership_type_id" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Membresía</label>
                                            <select name="membership_type_id" id="membership_type_id" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                @foreach(\App\Models\MembershipType::active()->get() as $type)
                                                    <option value="{{ $type->id }}" {{ $member->activeMembership->membership_type_id == $type->id ? 'selected' : '' }}>
                                                        {{ $type->name }} - {{ number_format($type->price, 2) }} {{ $type->currency }} / {{ $type->duration_days }} días
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="document.getElementById('renew-membership-form').classList.add('hidden')"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Cancelar
                                            </button>
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Confirmar Renovación
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Pestaña de Historial de Membresías -->
            <div id="tab-content-memberships" class="tab-content hidden">
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    @if($member->memberships->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($member->memberships->sortByDesc('start_date') as $membership)
                            <li class="{{ $membership->status == 'active' ? 'bg-blue-50' : '' }}">
                                <a href="#" class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                {{ $membership->membershipType->name }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $membership->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ strtoupper($membership->status) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <i class="bi bi-calendar3 mr-1.5"></i>
                                                    {{ $membership->start_date->format('d/m/Y') }} - {{ $membership->end_date->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <i class="bi bi-currency-dollar mr-1.5"></i>
                                                {{ number_format($membership->price, 2) }} {{ $membership->currency }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-credit-card text-4xl text-gray-400"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin membresías</h3>
                            <p class="mt-1 text-sm text-gray-500">Este socio no tiene membresías registradas.</p>
                            <div class="mt-6">
                                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="bi bi-plus-lg mr-2"></i> Agregar Membresía
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Pestaña de Asistencia -->
            <div id="tab-content-checkins" class="tab-content hidden">
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    @if($member->checkins->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($member->checkins->sortByDesc('checkin_at') as $checkin)
                            <li>
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $checkin->checkin_at->format('d/m/Y H:i') }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $checkin->type == 'checkin' ? 'ENTRADA' : 'SALIDA' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($checkin->notes)
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-500">{{ $checkin->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-calendar-check text-4xl text-gray-400"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin registros de asistencia</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontraron registros de asistencia para este socio.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Pestaña de Pagos -->
            <div id="tab-content-payments" class="tab-content hidden">
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    @if($member->payments->count() > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($member->payments->sortByDesc('payment_date') as $payment)
                            <li>
                                <a href="#" class="block hover:bg-gray-50">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                Pago #{{ $payment->id }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $payment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ strtoupper($payment->status) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <i class="bi bi-calendar3 mr-1.5"></i>
                                                    {{ $payment->payment_date->format('d/m/Y') }}
                                                </p>
                                                @if($payment->membership)
                                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    <i class="bi bi-credit-card mr-1.5"></i>
                                                    {{ $payment->membership->membershipType->name }}
                                                </p>
                                                @endif
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <i class="bi bi-currency-dollar mr-1.5"></i>
                                                {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-cash-coin text-4xl text-gray-400"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin registros de pagos</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontraron registros de pagos para este socio.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Funcionalidad de pestañas
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los botones de pestaña y contenidos
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Agregar evento de clic a cada botón de pestaña
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.id.replace('tab-', '');
                
                // Remover clase activa de todos los botones y contenidos
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'text-blue-600', 'border-blue-500');
                    btn.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Agregar clase activa al botón y contenido seleccionado
                this.classList.remove('text-gray-500', 'border-transparent', 'hover:text-gray-700', 'hover:border-gray-300');
                this.classList.add('active', 'text-blue-600', 'border-blue-500');
                
                const activeTabContent = document.getElementById('tab-content-' + tabId);
                if (activeTabContent) {
                    activeTabContent.classList.remove('hidden');
                }
            });
        });
        
        // Activar la primera pestaña por defecto
        if (tabButtons.length > 0) {
            tabButtons[0].click();
        }
    });
</script>
@endpush

<style>
    .tab-button {
        border-bottom-width: 2px;
        transition: all 0.2s ease-in-out;
    }
    
    .tab-button:hover {
        border-bottom-color: #d1d5db;
    }
    
    .tab-button.active {
        border-bottom-color: #3b82f6;
        color: #2563eb;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
</style>

@endsection
