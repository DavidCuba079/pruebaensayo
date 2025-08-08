@php
    $isEdit = isset($checkIn);
    $checkIn = $checkIn ?? new \App\Models\CheckIn();
    $member = $member ?? $checkIn->member;
    
    $route = $isEdit 
        ? route('admin.check-ins.update', $checkIn)
        : route('admin.check-ins.store');
    
    $method = $isEdit ? 'PUT' : 'POST';
    $buttonText = $isEdit ? 'Actualizar' : 'Registrar';
    $cardTitle = $isEdit ? 'Editar Registro' : 'Nuevo Registro';
    
    // Obtener el tipo de membresía activa si existe
    $activeMembership = $member->activeMembership ?? null;
    $hasActiveMembership = $activeMembership && $activeMembership->isActive();
    
    // Determinar si mostrar advertencia de membresía
    $showMembershipWarning = !$hasActiveMembership && !$isEdit;
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $cardTitle }}</h3>
    </div>
    
    <div class="card-body">
        @if($showMembershipWarning)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>¡Atención!</strong> Este miembro no tiene una membresía activa. 
                @if($activeMembership && $activeMembership->isExpired())
                    La última membresía expiró el {{ $activeMembership->end_date->format('d/m/Y') }}.
                @endif
            </div>
        @endif
        
        <form action="{{ $route }}" method="POST" id="checkInForm">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            @if($isEdit)
                <div class="form-group">
                    <label>ID del Registro</label>
                    <input type="text" class="form-control" value="#{{ str_pad($checkIn->id, 6, '0', STR_PAD_LEFT) }}" readonly>
                </div>
            @endif
            
            <!-- Información del miembro (solo lectura) -->
            <div class="form-group">
                <label>Miembro</label>
                @if($isEdit)
                    <input type="text" class="form-control" value="{{ $member->full_name }} ({{ $member->dni }})" readonly>
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                @else
                    <div class="input-group">
                        <input type="text" id="memberSearch" class="form-control" 
                               placeholder="Buscar por nombre o DNI" value="{{ $member ? $member->full_name . ' (' . $member->dni . ')' : '' }}" 
                               {{ $member ? 'readonly' : '' }}>
                        <input type="hidden" name="member_id" id="memberId" value="{{ $member->id ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="searchMemberBtn">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div id="memberSearchResults" class="mt-2" style="display: none;">
                        <div class="list-group" id="memberResultsList">
                            <!-- Resultados de búsqueda se cargarán aquí -->
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Información de la membresía (solo lectura) -->
            @if($activeMembership)
                <div class="form-group">
                    <label>Membresía</label>
                    <div class="input-group">
                        <input type="text" class="form-control" 
                               value="{{ $activeMembership->membershipType->name }} ({{ $activeMembership->start_date->format('d/m/Y') }} - {{ $activeMembership->end_date->format('d/m/Y') }})" 
                               readonly>
                        <div class="input-group-append">
                            <a href="{{ route('admin.memberships.show', $activeMembership) }}" 
                               class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Fecha y hora del registro -->
            <div class="form-group">
                <label for="check_in_at">Fecha y Hora</label>
                <div class="input-group date" id="datetimepicker">
                    <input type="datetime-local" 
                           class="form-control @error('check_in_at') is-invalid @enderror" 
                           id="check_in_at" 
                           name="check_in_at" 
                           value="{{ old('check_in_at', $isEdit ? $checkIn->check_in_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                           required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    @error('check_in_at')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <small class="form-text text-muted">
                    Hora actual del servidor: {{ now()->format('d/m/Y H:i:s') }}
                </small>
            </div>
            
            <!-- Tipo de acceso -->
            <div class="form-group">
                <label for="access_type">Tipo de Acceso</label>
                <select name="access_type" id="access_type" 
                        class="form-control @error('access_type') is-invalid @enderror" 
                        required {{ !$hasActiveMembership && !$isEdit ? 'disabled' : '' }}>
                    <option value="">Seleccione un tipo</option>
                    <option value="check_in" {{ old('access_type', $checkIn->access_type) === 'check_in' ? 'selected' : '' }}>Entrada</option>
                    <option value="check_out" {{ old('access_type', $checkIn->access_type) === 'check_out' ? 'selected' : '' }}>Salida</option>
                </select>
                @error('access_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                
                @if(!$hasActiveMembership && !$isEdit)
                    <input type="hidden" name="access_type" value="check_in">
                    <small class="form-text text-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Solo se permite registrar entradas para miembros sin membresía activa.
                    </small>
                @endif
            </div>
            
            <!-- Notas -->
            <div class="form-group">
                <label for="notes">Notas (Opcional)</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="form-control @error('notes') is-invalid @enderror"
                          placeholder="Ej: Ingresó con invitado, olvidó su tarjeta, etc.">{{ old('notes', $checkIn->notes) }}</textarea>
                @error('notes')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <!-- Información de auditoría (solo para edición) -->
            @if($isEdit)
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Información de Auditoría</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Creado por:</strong> {{ $checkIn->created_by_user->name ?? 'Sistema' }}</p>
                            <p class="mb-1"><strong>Fecha de creación:</strong> {{ $checkIn->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($checkIn->updated_by_user)
                                <p class="mb-1"><strong>Última actualización por:</strong> {{ $checkIn->updated_by_user->name }}</p>
                            @endif
                            <p class="mb-1"><strong>Última actualización:</strong> {{ $checkIn->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="form-group text-right mt-4">
                <a href="{{ $isEdit ? route('admin.check-ins.show', $checkIn) : route('admin.check-ins.index') }}" 
                   class="btn btn-outline-secondary mr-2">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn" {{ !$hasActiveMembership && !$isEdit ? 'disabled' : '' }}>
                    <i class="fas fa-{{ $isEdit ? 'save' : 'plus' }}"></i> {{ $buttonText }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar datetimepicker
            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                locale: 'es',
                sideBySide: true,
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'fas fa-trash',
                    close: 'fas fa-times'
                }
            });
            
            // Validar fechas futuras
            $('#checkInForm').on('submit', function(e) {
                const checkInAt = new Date($('#check_in_at').val());
                const now = new Date();
                
                // No permitir fechas futuras (con un margen de 5 minutos)
                const fiveMinutesFromNow = new Date(now.getTime() + 5 * 60000);
                
                if (checkInAt > fiveMinutesFromNow) {
                    e.preventDefault();
                    alert('No se puede registrar una fecha futura. Por favor, verifica la fecha y hora.');
                    return false;
                }
                
                return true;
            });
            
            // Búsqueda de miembros (solo para creación)
            @if(!$isEdit)
                // Buscar miembros al hacer clic en el botón de búsqueda
                $('#searchMemberBtn').click(function() {
                    searchMembers();
                });
                
                // También buscar al presionar Enter en el campo de búsqueda
                $('#memberSearch').keypress(function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        searchMembers();
                        return false;
                    }
                });
                
                // Función para buscar miembros
                function searchMembers() {
                    const query = $('#memberSearch').val().trim();
                    
                    if (query.length < 2) {
                        alert('Por favor ingresa al menos 2 caracteres para buscar.');
                        return;
                    }
                    
                    // Mostrar indicador de carga
                    $('#memberSearchResults').hide();
                    
                    // Realizar la búsqueda AJAX
                    $.ajax({
                        url: '{{ route("admin.check-ins.search-member") }}',
                        type: 'GET',
                        data: { q: query },
                        dataType: 'json',
                        success: function(response) {
                            const memberList = $('#memberResultsList');
                            memberList.empty();
                            
                            if (response.length > 0) {
                                response.forEach(function(member) {
                                    const memberItem = `
                                        <a href="#" class="list-group-item list-group-item-action member-item" 
                                           data-member-id="${member.id}"
                                           data-member-name="${member.text}"
                                           data-has-membership="${member.has_active_membership}"
                                           data-membership-ends="${member.membership_ends || ''}">
                                            <div class="d-flex align-items-center">
                                                <img src="${member.photo || '{{ asset("images/default-avatar.png") }}'}" 
                                                     class="rounded-circle mr-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     alt="${member.text}">
                                                <div>
                                                    <h6 class="mb-0">${member.text}</h6>
                                                    <small class="text-muted">
                                                        ${member.has_active_membership ? 
                                                            `<span class="text-success"><i class="fas fa-check-circle"></i> Membresía activa hasta ${member.membership_ends}</span>` : 
                                                            '<span class="text-danger"><i class="fas fa-times-circle"></i> Sin membresía activa</span>'}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    `;
                                    memberList.append(memberItem);
                                });
                                
                                $('#memberSearchResults').show();
                            } else {
                                memberList.append(`
                                    <div class="list-group-item text-center text-muted">
                                        No se encontraron miembros que coincidan con la búsqueda.
                                    </div>
                                `);
                                $('#memberSearchResults').show();
                            }
                        },
                        error: function() {
                            alert('Error al buscar miembros. Inténtalo de nuevo.');
                        }
                    });
                }
                
                // Seleccionar un miembro de los resultados
                $(document).on('click', '.member-item', function(e) {
                    e.preventDefault();
                    
                    const memberId = $(this).data('member-id');
                    const memberName = $(this).data('member-name');
                    const hasMembership = $(this).data('has-membership');
                    
                    // Actualizar el campo de búsqueda y el ID oculto
                    $('#memberSearch').val(memberName).attr('readonly', true);
                    $('#memberId').val(memberId);
                    
                    // Ocultar resultados
                    $('#memberSearchResults').hide();
                    
                    // Habilitar/deshabilitar el botón de envío según la membresía
                    if (hasMembership) {
                        $('#submitBtn').prop('disabled', false);
                        $('#access_type').prop('disabled', false);
                    } else {
                        $('#submitBtn').prop('disabled', false);
                        $('#access_type').val('check_in').prop('disabled', true);
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'access_type',
                            value: 'check_in'
                        }).appendTo('#checkInForm');
                    }
                    
                    // Mostrar mensaje de advertencia si no hay membresía activa
                    if (!hasMembership) {
                        if ($('#membershipWarning').length === 0) {
                            $('<div>').attr('id', 'membershipWarning')
                                .addClass('alert alert-warning mt-3')
                                .html(`
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>¡Atención!</strong> Este miembro no tiene una membresía activa. 
                                    Solo se permite registrar entradas.
                                `)
                                .insertAfter('#memberSearch');
                        }
                    } else {
                        $('#membershipWarning').remove();
                    }
                });
            @endif
        });
    </script>
@endpush
