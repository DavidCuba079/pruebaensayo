@extends('admin.layout')

@section('title', 'Editar Membresía')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado con botón de volver -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Membresía</h2>
            <p class="text-sm text-gray-600">Actualiza la información de la membresía.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.memberships.show', $membership) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Volver al detalle
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.memberships.update', $membership) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Información de la Membresía -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Membresía</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Información del Socio (solo lectura) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Socio</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        @if($membership->member->profile_photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ Storage::url($membership->member->profile_photo_path) }}" 
                                                 alt="{{ $membership->member->full_name }}">
                                        @else
                                            <i class="bi bi-person text-blue-600"></i>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $membership->member->full_name }}</p>
                                        <p class="text-sm text-gray-500">DNI: {{ $membership->member->dni }}</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="member_id" value="{{ $membership->member_id }}">
                        </div>
                        
                        <!-- Tipo de Membresía -->
                        <div>
                            <label for="membership_type_id" class="block text-sm font-medium text-gray-700">Tipo de Membresía <span class="text-red-600">*</span></label>
                            <select name="membership_type_id" id="membership_type_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('membership_type_id') border-red-500 @enderror"
                                onchange="updateMembershipDetails()">
                                @foreach(\App\Models\MembershipType::all() as $type)
                                    <option value="{{ $type->id }}" 
                                            data-duration="{{ $type->duration_days }}" 
                                            data-price="{{ $type->price }}"
                                            data-currency="{{ $type->currency }}"
                                            {{ old('membership_type_id', $membership->membership_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} - {{ number_format($type->price, 2) }} {{ $type->currency }} / {{ $type->duration_days }} días
                                    </option>
                                @endforeach
                            </select>
                            @error('membership_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Estado -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado <span class="text-red-600">*</span></label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $membership->status) == 'active' ? 'selected' : '' }}>Activa</option>
                                <option value="expired" {{ old('status', $membership->status) == 'expired' ? 'selected' : '' }}>Expirada</option>
                                <option value="cancelled" {{ old('status', $membership->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fecha de Inicio -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio <span class="text-red-600">*</span></label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ old('start_date', $membership->start_date->format('Y-m-d')) }}" 
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_date') border-red-500 @enderror"
                                   onchange="updateEndDate()">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fecha de Fin -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Fin <span class="text-red-600">*</span></label>
                            <input type="date" name="end_date" id="end_date" 
                                   value="{{ old('end_date', $membership->end_date->format('Y-m-d')) }}" 
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Precio -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Precio <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="currency_symbol">
                                        {{ $membership->currency == 'USD' ? '$' : '$' }}
                                    </span>
                                </div>
                                <input type="number" name="price" id="price" step="0.01" min="0" required
                                       value="{{ old('price', number_format($membership->price, 2, '.', '')) }}"
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md @error('price') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="price_currency">
                                        {{ $membership->currency }}
                                    </span>
                                </div>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Moneda -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Moneda <span class="text-red-600">*</span></label>
                            <select name="currency" id="currency" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('currency') border-red-500 @enderror">
                                <option value="MXN" {{ old('currency', $membership->currency) == 'MXN' ? 'selected' : '' }}>Pesos Mexicanos (MXN)</option>
                                <option value="USD" {{ old('currency', $membership->currency) == 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Método de Pago -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago <span class="text-red-600">*</span></label>
                            <select name="payment_method" id="payment_method" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('payment_method') border-red-500 @enderror">
                                <option value="cash" {{ old('payment_method', $membership->payment_method) == 'cash' ? 'selected' : '' }}>Efectivo</option>
                                <option value="card" {{ old('payment_method', $membership->payment_method) == 'card' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                                <option value="transfer" {{ old('payment_method', $membership->payment_method) == 'transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="other" {{ old('payment_method', $membership->payment_method) == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Estado del Pago -->
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700">Estado del Pago <span class="text-red-600">*</span></label>
                            <select name="payment_status" id="payment_status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('payment_status') border-red-500 @enderror">
                                <option value="pending" {{ old('payment_status', $membership->payment_status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completed" {{ old('payment_status', $membership->payment_status) == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="failed" {{ old('payment_status', $membership->payment_status) == 'failed' ? 'selected' : '' }}>Fallido</option>
                                <option value="refunded" {{ old('payment_status', $membership->payment_status) == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fecha de Pago -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Fecha de Pago <span class="text-red-600">*</span></label>
                            <input type="datetime-local" name="payment_date" id="payment_date" 
                                   value="{{ old('payment_date', $membership->payment_date ? $membership->payment_date->format('Y-m-d\TH:i') : '') }}" 
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('payment_date') border-red-500 @enderror">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Referencia de Pago -->
                        <div>
                            <label for="payment_reference" class="block text-sm font-medium text-gray-700">Referencia de Pago</label>
                            <input type="text" name="payment_reference" id="payment_reference" 
                                   value="{{ old('payment_reference', $membership->payment_reference) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('payment_reference') border-red-500 @enderror">
                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Notas -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes', $membership->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-between pt-6">
                    <div>
                        <button type="button" 
                                onclick="if(confirm('¿Estás seguro de que deseas cancelar esta membresía?')) { document.getElementById('cancel-form').submit(); }" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="bi bi-x-circle-fill mr-2"></i> Cancelar Membresía
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.memberships.show', $membership) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="bi bi-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Formulario oculto para cancelar membresía -->
        <form id="cancel-form" action="{{ route('admin.memberships.cancel', $membership) }}" method="POST" class="hidden">
            @csrf
            @method('PUT')
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Función para actualizar la fecha de fin según la duración de la membresía
    function updateEndDate() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const membershipTypeSelect = document.getElementById('membership_type_id');
        
        if (!startDateInput.value || !membershipTypeSelect.value) return;
        
        const startDate = new Date(startDateInput.value);
        const selectedOption = membershipTypeSelect.options[membershipTypeSelect.selectedIndex];
        const durationDays = parseInt(selectedOption.getAttribute('data-duration')) || 0;
        
        if (durationDays > 0) {
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + durationDays);
            
            // Formatear la fecha como YYYY-MM-DD
            const formattedDate = endDate.toISOString().split('T')[0];
            endDateInput.value = formattedDate;
        }
    }
    
    // Función para actualizar los detalles de la membresía según el tipo seleccionado
    function updateMembershipDetails() {
        const membershipTypeSelect = document.getElementById('membership_type_id');
        const priceInput = document.getElementById('price');
        const currencySpan = document.getElementById('price_currency');
        const currencySymbol = document.getElementById('currency_symbol');
        const currencySelect = document.getElementById('currency');
        
        if (membershipTypeSelect.value) {
            const selectedOption = membershipTypeSelect.options[membershipTypeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || '0';
            const currency = selectedOption.getAttribute('data-currency') || 'MXN';
            
            // Si el precio no ha sido modificado manualmente, actualizarlo
            if (priceInput.value === '' || priceInput.value === '{{ number_format($membership->price, 2, '.', '') }}') {
                priceInput.value = parseFloat(price).toFixed(2);
            }
            
            // Actualizar moneda
            currencySelect.value = currency;
            currencySpan.textContent = currency;
            currencySymbol.textContent = currency === 'USD' ? '$' : '$';
            
            // Actualizar fecha de fin
            updateEndDate();
        }
    }
    
    // Inicializar la vista con los valores por defecto
    document.addEventListener('DOMContentLoaded', function() {
        updateMembershipDetails();
    });
</script>
@endpush

@endsection
