@extends('admin.layout')

@section('title', 'Nueva Membresía')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado con botón de volver -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Nueva Membresía</h2>
            <p class="text-sm text-gray-600">Registra una nueva membresía para un socio.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.memberships.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.memberships.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Información de la Membresía -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Membresía</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Búsqueda de Socio -->
                        <div class="md:col-span-2">
                            <label for="member_search" class="block text-sm font-medium text-gray-700">Buscar Socio <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-search text-gray-400"></i>
                                </div>
                                <input type="text" id="member_search" 
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Nombre, apellido o DNI"
                                       autocomplete="off">
                                <input type="hidden" name="member_id" id="member_id" required>
                                <div id="member_results" class="hidden absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto"></div>
                            </div>
                            <div id="selected_member" class="mt-2 p-3 bg-blue-50 rounded-md hidden">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-person text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900" id="selected_member_name"></p>
                                        <p class="text-sm text-gray-500" id="selected_member_dni"></p>
                                    </div>
                                    <button type="button" onclick="clearMemberSelection()" class="ml-auto text-gray-400 hover:text-gray-500">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @error('member_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tipo de Membresía -->
                        <div>
                            <label for="membership_type_id" class="block text-sm font-medium text-gray-700">Tipo de Membresía <span class="text-red-600">*</span></label>
                            <select name="membership_type_id" id="membership_type_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('membership_type_id') border-red-500 @enderror"
                                onchange="updateMembershipDetails()">
                                <option value="">Seleccione un tipo</option>
                                @foreach(\App\Models\MembershipType::active()->get() as $type)
                                    <option value="{{ $type->id }}" 
                                            data-duration="{{ $type->duration_days }}" 
                                            data-price="{{ $type->price }}"
                                            data-currency="{{ $type->currency }}"
                                            {{ old('membership_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} - {{ number_format($type->price, 2) }} {{ $type->currency }} / {{ $type->duration_days }} días
                                    </option>
                                @endforeach
                            </select>
                            @error('membership_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fecha de Inicio -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio <span class="text-red-600">*</span></label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}" 
                                   min="{{ now()->format('Y-m-d') }}" 
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_date') border-red-500 @enderror"
                                   onchange="updateEndDate()">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Fecha de Fin (calculada) -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Fin <span class="text-red-600">*</span></label>
                            <input type="date" name="end_date" id="end_date" 
                                   value="{{ old('end_date') }}" 
                                   readonly
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-50 sm:text-sm @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Precio -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Precio <span class="text-red-600">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="currency_symbol">$</span>
                                </div>
                                <input type="number" name="price" id="price" step="0.01" min="0" required
                                       value="{{ old('price') }}"
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md @error('price') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="price_currency">MXN</span>
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
                                <option value="MXN" {{ old('currency', 'MXN') == 'MXN' ? 'selected' : '' }}>Pesos Mexicanos (MXN)</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>Dólares (USD)</option>
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
                                <option value="">Seleccione un método</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Otro</option>
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
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completed" {{ old('payment_status', 'completed') == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                                <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Notas -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('admin.memberships.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="bi bi-save mr-2"></i> Guardar Membresía
                    </button>
                </div>
            </div>
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
            
            // Actualizar precio
            priceInput.value = parseFloat(price).toFixed(2);
            
            // Actualizar moneda
            currencySelect.value = currency;
            currencySpan.textContent = currency;
            currencySymbol.textContent = currency === 'USD' ? '$' : '$';
            
            // Actualizar fecha de fin
            updateEndDate();
        } else {
            priceInput.value = '';
            currencySpan.textContent = '$';
            currencySelect.value = 'MXN';
            document.getElementById('end_date').value = '';
        }
    }
    
    // Función para buscar socios
    document.getElementById('member_search').addEventListener('input', function(e) {
        const query = e.target.value.trim();
        const resultsDiv = document.getElementById('member_results');
        
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }
        
        // Realizar la búsqueda AJAX
        fetch(`/admin/members/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = '';
                    data.forEach(member => {
                        html += `
                            <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer" 
                                 onclick="selectMember(${member.id}, '${member.first_name} ${member.last_name}', '${member.dni}')">
                                <div class="font-medium text-gray-900">${member.first_name} ${member.last_name}</div>
                                <div class="text-sm text-gray-500">DNI: ${member.dni}</div>
                            </div>
                        `;
                    });
                    resultsDiv.innerHTML = html;
                    resultsDiv.classList.remove('hidden');
                } else {
                    resultsDiv.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">No se encontraron socios</div>';
                    resultsDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.classList.add('hidden');
            });
    });
    
    // Función para seleccionar un socio de los resultados de búsqueda
    function selectMember(id, name, dni) {
        document.getElementById('member_id').value = id;
        document.getElementById('member_search').value = `${name} (${dni})`;
        document.getElementById('member_results').classList.add('hidden');
        
        // Mostrar tarjeta del socio seleccionado
        document.getElementById('selected_member_name').textContent = name;
        document.getElementById('selected_member_dni').textContent = `DNI: ${dni}`;
        document.getElementById('selected_member').classList.remove('hidden');
    }
    
    // Función para limpiar la selección de socio
    function clearMemberSelection() {
        document.getElementById('member_id').value = '';
        document.getElementById('member_search').value = '';
        document.getElementById('selected_member').classList.add('hidden');
    }
    
    // Cerrar resultados de búsqueda al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#member_search') && !e.target.closest('#member_results')) {
            document.getElementById('member_results').classList.add('hidden');
        }
    });
    
    // Inicializar la vista con los valores por defecto
    document.addEventListener('DOMContentLoaded', function() {
        updateMembershipDetails();
    });
</script>
@endpush

@endsection
