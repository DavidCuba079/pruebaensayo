@extends('admin.layout')

@section('title', 'Detalle de Membresía')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado con acciones -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detalle de Membresía</h2>
            <div class="flex items-center mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $membership->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $membership->status == 'expired' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $membership->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ strtoupper($membership->status) }}
                </span>
                <span class="ml-2 text-sm text-gray-500">
                    #{{ str_pad($membership->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('admin.memberships.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Volver
            </a>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                    <i class="bi bi-three-dots-vertical mr-1"></i> Acciones
                </button>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                    <div class="py-1" role="none">
                        <a href="{{ route('admin.memberships.edit', $membership) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                            <i class="bi bi-pencil-square mr-2"></i> Editar Membresía
                        </a>
                        @if($membership->status == 'active')
                        <a href="{{ route('admin.memberships.renew', $membership) }}" class="text-blue-700 block px-4 py-2 text-sm hover:bg-blue-50" role="menuitem">
                            <i class="bi bi-arrow-repeat mr-2"></i> Renovar Membresía
                        </a>
                        @endif
                        @if($membership->status != 'cancelled')
                        <button type="button" 
                                onclick="if(confirm('¿Estás seguro de que deseas cancelar esta membresía?')) { document.getElementById('cancel-form').submit(); }" 
                                class="w-full text-left text-red-700 block px-4 py-2 text-sm hover:bg-red-50" 
                                role="menuitem">
                            <i class="bi bi-x-circle mr-2"></i> Cancelar Membresía
                        </button>
                        @endif
                        <form id="cancel-form" action="{{ route('admin.memberships.cancel', $membership) }}" method="POST" class="hidden">
                            @csrf
                            @method('PUT')
                        </form>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="#" onclick="window.print()" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                            <i class="bi bi-printer mr-2"></i> Imprimir Comprobante
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <!-- Información General -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Información General
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Detalles básicos de la membresía y el socio.
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Socio</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($membership->member->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ Storage::url($membership->member->profile_photo_path) }}" 
                                         alt="{{ $membership->member->full_name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="bi bi-person text-gray-500"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ $membership->member->full_name }}</div>
                                <div class="text-sm text-gray-500">DNI: {{ $membership->member->dni }}</div>
                            </div>
                        </div>
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Tipo de Membresía</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $membership->membershipType->name }} ({{ $membership->membershipType->duration_days }} días)
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Período</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        Del {{ $membership->start_date->format('d/m/Y') }} al {{ $membership->end_date->format('d/m/Y') }}
                        @if($membership->status == 'active')
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $membership->days_remaining }} días restantes
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $membership->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $membership->status == 'expired' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $membership->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ strtoupper($membership->status) }}
                        </span>
                        @if($membership->cancelled_at)
                            <div class="mt-1 text-sm text-gray-500">
                                Cancelada el: {{ $membership->cancelled_at->format('d/m/Y H:i') }}
                                @if($membership->cancellation_reason)
                                    <div class="mt-1"><span class="font-medium">Motivo:</span> {{ $membership->cancellation_reason }}</div>
                                @endif
                            </div>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Información de Pago -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Información de Pago
                </h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Monto</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $membership->formatted_price }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Método de Pago</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ ucfirst($membership->payment_method) }}
                            @if($membership->payment_reference)
                                <div class="text-sm text-gray-500 mt-1">
                                    Ref: {{ $membership->payment_reference }}
                                </div>
                            @endif
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Estado del Pago</dt>
                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $membership->payment_status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $membership->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $membership->payment_status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $membership->payment_status == 'refunded' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ strtoupper($membership->payment_status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Pago</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $membership->payment_date ? $membership->payment_date->format('d/m/Y H:i') : 'No especificada' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Historial de Visitas -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Historial de Visitas
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Últimas visitas registradas para esta membresía.
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                @if($membership->checkIns->count() > 0)
                    <div class="overflow-hidden">
                        <ul class="divide-y divide-gray-200">
                            @foreach($membership->checkIns->sortByDesc('check_in_at')->take(5) as $checkIn)
                                <li class="py-4 px-6 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <i class="bi bi-box-arrow-in-right text-green-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $checkIn->check_in_at->format('d/m/Y H:i') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $checkIn->user ? 'Registrado por: ' . $checkIn->user->name : 'Registro automático' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($checkIn->notes)
                                            <div class="text-sm text-gray-500" x-data="{ showNotes: false }">
                                                <button @click="showNotes = !showNotes" class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="bi bi-chat-square-text"></i> Ver notas
                                                </button>
                                                <div x-show="showNotes" @click.away="showNotes = false" class="mt-2 p-2 text-sm bg-gray-50 border border-gray-200 rounded-md">
                                                    {{ $checkIn->notes }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($membership->checkIns->count() > 5)
                        <div class="px-6 py-3 bg-gray-50 text-right text-sm font-medium">
                            <a href="#" class="text-blue-600 hover:text-blue-900">Ver todas las visitas ({{ $membership->checkIns->count() }})</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-calendar-x text-4xl text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin visitas registradas</h3>
                        <p class="mt-1 text-sm text-gray-500">No se han registrado visitas para esta membresía.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notas Adicionales -->
    @if($membership->notes)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Notas Adicionales
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <p class="text-gray-700 whitespace-pre-line">{{ $membership->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Historial de Cambios -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Historial de Cambios
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Registro de modificaciones realizadas a esta membresía.
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            @if($membership->audits->count() > 0)
                <div class="overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($membership->audits->sortByDesc('created_at')->take(5) as $audit)
                            <li class="py-4 px-6 hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-clock-history text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $audit->user ? $audit->user->name : 'Sistema' }}
                                            <span class="text-gray-500 font-normal">
                                                @if($audit->event == 'created')
                                                    creó esta membresía
                                                @elseif($audit->event == 'updated')
                                                    actualizó la membresía
                                                @elseif($audit->event == 'deleted')
                                                    eliminó esta membresía
                                                @elseif($audit->event == 'restored')
                                                    restauró esta membresía
                                                @endif
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $audit->created_at->diffForHumans() }}
                                        </div>
                                        @if($audit->event == 'updated' && count($audit->getModified()) > 0)
                                            <div class="mt-2 text-sm">
                                                @foreach($audit->getModified() as $attribute => $modified)
                                                    <div class="mb-1">
                                                        <span class="font-medium">{{ $attribute }}:</span>
                                                        <span class="line-through text-red-500">{{ $modified['old'] ?? 'N/A' }}</span>
                                                        <span>→</span>
                                                        <span class="text-green-600">{{ $modified['new'] ?? 'N/A' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if($membership->audits->count() > 5)
                    <div class="px-6 py-3 bg-gray-50 text-right text-sm font-medium">
                        <a href="#" class="text-blue-600 hover:text-blue-900">Ver historial completo ({{ $membership->audits->count() }})</a>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="bi bi-clock-history text-4xl text-gray-400"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin historial de cambios</h3>
                    <p class="mt-1 text-sm text-gray-500">No se han registrado cambios para esta membresía.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<!-- Alpine.js para interactividad -->
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@endpush

@endsection
