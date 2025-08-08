@extends('admin.layout')

@section('title', 'Registrar Entrada/Salida')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registrar Entrada/Salida</h1>
        <a href="{{ route('admin.check-ins.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buscar Miembro</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="member_search">Buscar por nombre o DNI</label>
                        <div class="input-group">
                            <input type="text" id="member_search" class="form-control" 
                                   placeholder="Escribe el nombre o DNI del miembro">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="search_button">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="search_results" class="mt-3" style="display: none;">
                        <h5>Resultados de la búsqueda:</h5>
                        <div class="list-group" id="member_list">
                            <!-- Los resultados se cargarán aquí dinámicamente -->
                        </div>
                    </div>
                    
                    <div id="no_results" class="alert alert-warning mt-3" style="display: none;">
                        No se encontraron miembros que coincidan con la búsqueda.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card" id="member_details_card" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Registro</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4" id="member_photo_container">
                        <img id="member_photo" src="" alt="Foto del miembro" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    
                    <form action="{{ route('admin.check-ins.store') }}" method="POST" id="checkin_form">
                        @csrf
                        <input type="hidden" name="member_id" id="member_id">
                        
                        <div class="form-group">
                            <label for="member_name">Miembro</label>
                            <input type="text" id="member_name" class="form-control" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="membership_status">Estado de la Membresía</label>
                            <div id="membership_status" class="alert">
                                <!-- Se actualizará dinámicamente -->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="access_type">Tipo de Acceso</label>
                            <select name="access_type" id="access_type" class="form-control" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="check_in">Entrada</option>
                                <option value="check_out">Salida</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notas (opcional)</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control" 
                                      placeholder="Ej: Ingresó con invitado"></textarea>
                        </div>
                        
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary" id="submit_button" disabled>
                                <i class="fas fa-save"></i> Guardar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card" id="no_member_selected" style="display: block;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-search fa-3x text-muted mb-3"></i>
                    <h5>Busca un miembro para registrar su acceso</h5>
                    <p class="text-muted">
                        Utiliza el formulario de búsqueda para encontrar al miembro por su nombre o DNI.
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <style>
        .member-card {
            cursor: pointer;
            transition: all 0.2s;
        }
        .member-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .member-card.active {
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
        }
        .member-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        #member_photo {
            border: 3px solid #dee2e6;
            transition: all 0.3s;
        }
        #member_photo:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Variables globales
            let selectedMember = null;
            
            // Buscar miembros al hacer clic en el botón de búsqueda
            $('#search_button').click(function() {
                searchMembers();
            });
            
            // También buscar al presionar Enter en el campo de búsqueda
            $('#member_search').keypress(function(e) {
                if (e.which === 13) {
                    searchMembers();
                    return false;
                }
            });
            
            // Función para buscar miembros
            function searchMembers() {
                const query = $('#member_search').val().trim();
                
                if (query.length < 2) {
                    showAlert('Por favor ingresa al menos 2 caracteres para buscar.', 'warning');
                    return;
                }
                
                // Mostrar indicador de carga
                $('#search_results').hide();
                $('#no_results').hide();
                
                // Realizar la búsqueda AJAX
                $.ajax({
                    url: '{{ route("admin.check-ins.search-member") }}',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(response) {
                        const memberList = $('#member_list');
                        memberList.empty();
                        
                        if (response.length > 0) {
                            response.forEach(function(member) {
                                const memberItem = `
                                    <div class="list-group-item list-group-item-action member-card" 
                                         data-member-id="${member.id}"
                                         data-member-name="${member.text}"
                                         data-has-membership="${member.has_active_membership}"
                                         data-membership-ends="${member.membership_ends || ''}"
                                         data-photo="${member.photo || ''}">
                                        <div class="d-flex align-items-center">
                                            <img src="${member.photo || '{{ asset("images/default-avatar.png") }}'}" 
                                                 class="member-photo mr-3" 
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
                                    </div>
                                `;
                                memberList.append(memberItem);
                            });
                            
                            $('#search_results').show();
                            $('#no_results').hide();
                        } else {
                            $('#search_results').hide();
                            $('#no_results').show();
                        }
                    },
                    error: function() {
                        showAlert('Error al buscar miembros. Inténtalo de nuevo.', 'danger');
                    }
                });
            }
            
            // Seleccionar un miembro de los resultados
            $(document).on('click', '.member-card', function() {
                // Remover clase activa de todos los elementos
                $('.member-card').removeClass('active');
                
                // Agregar clase activa al elemento seleccionado
                $(this).addClass('active');
                
                // Obtener datos del miembro seleccionado
                selectedMember = {
                    id: $(this).data('member-id'),
                    name: $(this).data('member-name'),
                    hasMembership: $(this).data('has-membership'),
                    membershipEnds: $(this).data('membership-ends'),
                    photo: $(this).data('photo') || '{{ asset("images/default-avatar.png") }}'
                };
                
                // Actualizar el formulario con los datos del miembro
                updateMemberForm();
            });
            
            // Actualizar el formulario con los datos del miembro seleccionado
            function updateMemberForm() {
                if (!selectedMember) return;
                
                // Mostrar tarjeta de detalles y ocultar mensaje
                $('#member_details_card').show();
                $('#no_member_selected').hide();
                
                // Actualizar datos del formulario
                $('#member_id').val(selectedMember.id);
                $('#member_name').val(selectedMember.name);
                $('#member_photo').attr('src', selectedMember.photo);
                
                // Actualizar estado de la membresía
                const membershipStatus = $('#membership_status');
                
                if (selectedMember.hasMembership) {
                    membershipStatus.removeClass('alert-danger').addClass('alert-success');
                    membershipStatus.html(`
                        <i class="fas fa-check-circle"></i> 
                        <strong>Membresía activa</strong> hasta el ${selectedMember.membershipEnds}
                    `);
                    
                    // Habilitar el botón de envío
                    $('#submit_button').prop('disabled', false);
                } else {
                    membershipStatus.removeClass('alert-success').addClass('alert-danger');
                    membershipStatus.html(`
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Sin membresía activa</strong>. El miembro no podrá ingresar al gimnasio.
                    `);
                    
                    // Deshabilitar el botón de envío
                    $('#submit_button').prop('disabled', true);
                }
                
                // Verificar si el miembro tiene un check-in sin check-out
                checkActiveCheckIn(selectedMember.id);
            }
            
            // Verificar si el miembro tiene un check-in sin check-out
            function checkActiveCheckIn(memberId) {
                $.ajax({
                    url: '{{ route("admin.check-ins.index") }}',
                    type: 'GET',
                    data: {
                        member_id: memberId,
                        access_type: 'check_in',
                        active: 1
                    },
                    success: function(response) {
                        const accessTypeSelect = $('#access_type');
                        
                        // Si hay un check-in activo, deshabilitar la opción de entrada
                        if (response.data && response.data.length > 0) {
                            accessTypeSelect.find('option[value="check_in"]').prop('disabled', true);
                            accessTypeSelect.val('check_out');
                            
                            // Mostrar advertencia
                            showAlert('Este miembro ya tiene una entrada registrada sin salida. Se ha seleccionado automáticamente "Salida".', 'warning');
                        } else {
                            accessTypeSelect.find('option[value="check_in"]').prop('disabled', false);
                            accessTypeSelect.val('check_in');
                        }
                    }
                });
            }
            
            // Mostrar alerta
            function showAlert(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                // Insertar la alerta al principio del formulario
                $('#checkin_form').prepend(alertHtml);
                
                // Eliminar la alerta después de 5 segundos
                setTimeout(() => {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 5000);
            }
            
            // Validar el formulario antes de enviar
            $('#checkin_form').on('submit', function(e) {
                if (!selectedMember) {
                    e.preventDefault();
                    showAlert('Por favor selecciona un miembro.', 'warning');
                    return false;
                }
                
                if (!selectedMember.hasMembership) {
                    e.preventDefault();
                    showAlert('No se puede registrar el acceso: El miembro no tiene una membresía activa.', 'danger');
                    return false;
                }
                
                const accessType = $('#access_type').val();
                if (!accessType) {
                    e.preventDefault();
                    showAlert('Por favor selecciona un tipo de acceso.', 'warning');
                    return false;
                }
                
                // Mostrar confirmación
                const action = accessType === 'check_in' ? 'entrada' : 'salida';
                return confirm(`¿Estás seguro de registrar la ${action} para ${selectedMember.name}?`);
            });
        });
    </script>
@endpush
