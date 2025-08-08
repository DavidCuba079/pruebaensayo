@extends('admin.layout')

@section('title', 'Gestión de Membresías')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado con botón de agregar -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Membresías</h2>
            <p class="text-sm text-gray-600">Administra las membresías de los socios.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.memberships.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="bi bi-plus-lg mr-2"></i> Nueva Membresía
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
        <form action="{{ route('admin.memberships.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Búsqueda por nombre de socio -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar socio</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                               placeholder="Nombre o DNI">
                    </div>
                </div>
                
                <!-- Filtro por tipo de membresía -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Todos los tipos</option>
                        @foreach(\App\Models\MembershipType::all() as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Filtro por estado -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activas</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiradas</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                    </select>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-funnel mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.memberships.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-arrow-counterclockwise mr-2"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de membresías -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($memberships->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Socio
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fechas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($memberships as $membership)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $membership->member->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $membership->member->dni }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $membership->membershipType->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $membership->membershipType->duration_days }} días
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $membership->start_date->format('d/m/Y') }} - {{ $membership->end_date->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $membership->days_remaining }} días restantes
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($membership->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ACTIVA
                                    </span>
                                @elseif($membership->status == 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        EXPIRADA
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        CANCELADA
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($membership->price, 2) }} {{ $membership->currency }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.memberships.show', $membership) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3"
                                       title="Ver detalles">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.memberships.edit', $membership) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 mr-3"
                                       title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($membership->status == 'active')
                                    <button type="button" 
                                            onclick="confirmCancel('{{ route('admin.memberships.cancel', $membership) }}')"
                                            class="text-red-600 hover:text-red-900"
                                            title="Cancelar membresía">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $memberships->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="bi bi-credit-card text-4xl text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron membresías</h3>
                <p class="mt-1 text-sm text-gray-500 mb-4">No hay membresías que coincidan con los criterios de búsqueda.</p>
                <a href="{{ route('admin.memberships.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="bi bi-plus-lg mr-2"></i> Crear Membresía
                </a>
            </div>
        @endif
    </div>
</div>

@push('modals')
<!-- Modal de confirmación para cancelar membresía -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="cancelModal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Cancelar Membresía
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                ¿Estás seguro de que deseas cancelar esta membresía? Esta acción no se puede deshacer.
                            </p>
                        </div>
                        <form id="cancelForm" method="POST" class="mt-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700">Motivo de la cancelación</label>
                                <div class="mt-1">
                                    <textarea id="cancellation_reason" name="cancellation_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Opcional"></textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="refund" class="form-checkbox h-4 w-4 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Reembolsar pago</span>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('cancelForm').submit();" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirmar Cancelación
                </button>
                <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden');" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Función para mostrar el modal de confirmación de cancelación
    function confirmCancel(url) {
        const form = document.getElementById('cancelForm');
        form.action = url;
        document.getElementById('cancelModal').classList.remove('hidden');
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    });
</script>
@endpush

@endsection
