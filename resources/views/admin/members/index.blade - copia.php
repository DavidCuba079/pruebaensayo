@extends('layouts.app')

@section('title', 'Gestión de Ventas')

@push('css')
<style>
    .table-secondary {
        background-color: #f8f9fa !important;
        opacity: 0.7;
    }
    
    .disabled-link {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .modal-lg {
        max-width: 800px;
    }
    
    .stats-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .stats-info h6 {
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .stats-info p {
        margin-bottom: 0.25rem;
    }
    .cliente-pendiente {
        color: #dc3545 !important;
        font-weight: bold;
    }
    
    /* Estilos modernos para los botones de acciones */
    .btn-group .btn {
        transition: all 0.3s ease;
        border-radius: 6px;
        margin: 0 1px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn-group .btn i {
        font-size: 0.9rem;
        transition: transform 0.2s ease;
    }
    
    .btn-group .btn:hover i {
        transform: scale(1.1);
    }
    
    /* Colores específicos para cada acción */
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-color: #007bff;
    }
    
    .btn-outline-warning:hover {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        border-color: #ffc107;
        color: #212529;
    }
    
    .btn-outline-success:hover {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border-color: #28a745;
    }
    
    .btn-outline-info:hover {
        background: linear-gradient(135deg, #17a2b8, #117a8b);
        border-color: #17a2b8;
    }
    
    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #6c757d, #545b62);
        border-color: #6c757d;
    }
    
    .btn-dark:hover {
        background: linear-gradient(135deg, #343a40, #23272b);
        border-color: #343a40;
    }
    
    .btn-outline-danger:hover {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border-color: #dc3545;
    }
    
    /* Efecto de pulso para botones importantes */
    .btn-outline-success {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        50% { box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3); }
        100% { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        @if($cliente)
            Ventas del Cliente: {{ $cliente->persona->razon_social }}
        @else
            Gestión de Ventas
        @endif
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
        @if($cliente)
            <li class="breadcrumb-item active">{{ $cliente->persona->razon_social }}</li>
        @else
            <li class="breadcrumb-item active">Todas las Ventas</li>
        @endif
    </ol>

    @if($cliente)
    <!-- Información del cliente filtrado -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información del Cliente</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $cliente->persona->razon_social }}</p>
                    <p><strong>Dirección:</strong> {{ $cliente->persona->direccion ?? 'No especificada' }}</p>
                    <p><strong>Teléfono:</strong> {{ $cliente->persona->telefono ?? 'No especificado' }}</p>
                    <p><strong>Contacto:</strong> {{ $cliente->persona->contacto ?? 'No especificado' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total de Ventas:</strong> {{ $ventas->count() }}</p>
                    <p><strong>Total Facturado:</strong> Bs. {{ number_format($ventas->sum('total'), 2) }}</p>
                    <p><strong>Saldo Deudor:</strong> 
                        <span class="text-warning fw-bold">
                            Bs. {{ number_format($cliente->saldo_deudor, 2) }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver a Todas las Ventas
                </a>
                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Editar Cliente
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Cuadros resumen del mes actual -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $estadisticas['total_ventas_mes'] ?? 0 }}</h4>
                            <div>Total Ventas Mes</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $estadisticas['ventas_contado_mes'] ?? 0 }}</h4>
                            <div>Contado Mes</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $estadisticas['ventas_credito_mes'] ?? 0 }}</h4>
                            <div>Crédito Mes</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-credit-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">Bs. {{ number_format($estadisticas['total_monto_mes'] ?? 0, 2) }}</h4>
                            <div>Monto Facturado Mes</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros avanzados -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="buscar" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="buscar" name="buscar" value="{{ request('buscar') }}" placeholder="Cliente, comprobante...">
                    </div>
                    <div class="col-md-3">
                        <label for="forma_venta" class="form-label">Forma de Venta</label>
                        <select class="form-select" id="forma_venta" name="forma_venta">
                            <option value="">Todas</option>
                            <option value="CONTADO" {{ request('forma_venta') == 'CONTADO' ? 'selected' : '' }}>Contado</option>
                            <option value="CREDITO" {{ request('forma_venta') == 'CREDITO' ? 'selected' : '' }}>Crédito</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos</option>
                            <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Vigente</option>
                            <option value="2" {{ request('estado') == '2' ? 'selected' : '' }}>Cancelada</option>
                            <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Anulada</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Fecha Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Filtrar
                            </button>
                            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Botón para crear nueva venta -->
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('ventas.create') }}" class="btn btn-success btn-lg">
            <i class="fas fa-plus me-1"></i> Nueva Venta
        </a>
    </div>

    <!-- Tabla de ventas -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Lista de Ventas ({{ $ventas->count() }} registros)
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="ventasTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Comprobante</th>
                            <th>Fecha</th>
                            <th>Forma</th>
                            <th>Total</th>
                            <th>Saldo por Cobrar</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas->whereNotIn('estado', [0, 2]) as $venta)
                        @php
                            $tienePendiente = $venta->productos->contains(function($producto) {
                                return $producto->pivot->estado_detalle === 'PENDIENTE';
                            });
                        @endphp
                        <tr class="{{ $venta->estado == 2 ? 'table-secondary' : '' }} venta-row" data-show-url="{{ route('ventas.show', $venta->id) }}" data-venta-id="{{ $venta->id }}">
                            <td>{{ $venta->id }}</td>
                            <td>
                                <a href="#" class="ver-cliente fw-bold {{ $tienePendiente ? 'cliente-pendiente' : 'text-primary' }}" style="text-decoration: underline; cursor: pointer;" data-cliente-id="{{ $venta->cliente->id }}" data-cliente-nombre="{{ $venta->cliente->persona->razon_social ?? 'N/A' }}">
                                    {{ $venta->cliente->persona->razon_social ?? 'N/A' }}
                                </a>
                                <br><small class="text-muted">{{ $venta->cliente->persona->numero_documento ?? '' }}</small>
                            </td>
                            <td>{{ $venta->comprobante->tipo_comprobante ?? 'N/A' }}<br><small>{{ $venta->numero_comprobante }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y') }}</td>
                            <td>
                                @if($venta->forma_venta == 'CREDITO')
                                    <span class="badge bg-warning text-dark">CRÉDITO</span>
                                @else
                                    <span class="badge bg-success">CONTADO</span>
                                @endif
                            </td>
                            <td>Bs. {{ number_format($venta->total, 2) }}</td>
                            <td>
                                @if($venta->forma_venta == 'CREDITO')
                                    <span class="text-warning fw-bold">Bs. {{ number_format($venta->saldo_deudor, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($venta->estado == 1)
                                    <span class="badge bg-success">Vigente</span>
                                @elseif($venta->estado == 2)
                                    <span class="badge bg-secondary">Cancelada</span>
                                @elseif($venta->estado == 0)
                                    <span class="badge bg-danger">Anulada</span>
                                @else
                                    <span class="badge bg-secondary">Desconocido</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                    @if($venta->estado != 2 && $venta->estado != 3) <!-- Solo mostrar si no está cancelada ni anulada -->
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Anular venta" data-bs-toggle="modal" data-bs-target="#confirmAnulacionModal-{{ $venta->id }}">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                    @if($venta->estado == 1 || $venta->estado == 2)
                                    <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-outline-warning" title="Editar venta">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    @endif
                                    @if($venta->estado == 1)
                                    <!-- Botón que redirige directamente al detalle de la venta -->
                                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-success" title="Ver detalles de la venta">
                                        <i class="fas fa-cart-plus"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('ventas.index', ['cliente_id' => $venta->cliente->id]) }}" class="btn btn-sm btn-outline-info" title="Ver todas las ventas del cliente">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning ver-cliente" data-cliente-id="{{ $venta->cliente->id }}" title="Ver información del cliente">
                                        <i class="fas fa-user-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ver-cliente" data-cliente-id="{{ $venta->cliente->id }}" title="Ver perfil del cliente">
                                        <i class="fas fa-id-card"></i>
                                    </button>
                                    <a href="{{ route('ventas.generateInvoice', $venta->id) }}" class="btn btn-sm btn-dark" title="Imprimir PDF" target="_blank">
                                        <i class="fas fa-file-export"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Al final de la tabla o sección donde está el checkbox de descuento global -->
    <div class="form-check mt-3 mb-3">
        <input class="form-check-input" type="checkbox" id="enable-discount">
        <label class="form-check-label ms-2" for="enable-discount">
            Aplicar descuento global
        </label>
        <span class="ms-3 text-muted" style="font-size:0.95em;">
            (Si marcas esta opción podrás aplicar un descuento global a las ventas seleccionadas)
        </span>
    </div>
</div>

<!-- Modal para mostrar información del cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalClienteLabel">
            <i class="fas fa-user me-2"></i>Información del Cliente
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="stats-info">
                    <h6><i class="fas fa-user me-1"></i>Datos Personales</h6>
                    <p><strong>Nombre:</strong> <span id="modalClienteNombre"></span></p>
                    <p><strong>Dirección:</strong> <span id="modalClienteDireccion"></span></p>
                    <p><strong>Teléfono:</strong> <span id="modalClienteTelefono"></span></p>
                    <p><strong>Contacto:</strong> <span id="modalClienteContacto"></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-info">
                    <h6><i class="fas fa-chart-line me-1"></i>Estadísticas</h6>
                    <p><strong>Saldo Deudor:</strong> <span id="modalSaldoDeudor" class="text-warning fw-bold"></span></p>
                    <p><strong>Total de Ventas:</strong> <span id="modalTotalVentas"></span></p>
                    <p><strong>Última Venta:</strong> <span id="modalUltimaVenta"></span></p>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <a href="#" id="btnEditarCliente" class="btn btn-warning btn-lg me-2">
                <i class="fas fa-edit me-1"></i>Editar Cliente
            </a>
            <a href="#" id="btnVerVentasCliente" class="btn btn-primary btn-lg">
                <i class="fas fa-list me-1"></i>Ver Todas las Ventas del Cliente
            </a>
        </div>
      </div>
    </div>
  </div>
</div>

@foreach($ventas->whereNotIn('estado', [0, 2]) as $venta)
<!-- Modal de confirmación de anulación -->
<div class="modal fade" id="confirmAnulacionModal-{{ $venta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Anulación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea anular esta venta?</p>
                <p><strong>Número de Venta:</strong> {{ $venta->numero_comprobante }}</p>
                <p><strong>Cliente:</strong> {{ $venta->cliente->persona->razon_social }}</p>
                <p><strong>Total:</strong> Bs. {{ number_format($venta->total, 2) }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Anular Venta</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal de error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Error al Anular Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="errorContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Venta Anulada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="successContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

{{-- Incluir el modal de agregar producto y la sección de productos en segundo plano (como en create) --}}
@include('venta.partials.producto-modal')
@include('venta.partials.productos-section')

<form id="form-editar-productos" method="POST" style="display:none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="comprobante_id" id="edit_comprobante_id">
    <input type="hidden" name="numero_comprobante" id="edit_numero_comprobante">
    <input type="hidden" name="forma_venta" id="edit_forma_venta">
    <input type="hidden" name="cliente_id" id="edit_cliente_id">
    <input type="hidden" name="user_id" id="edit_user_id">
    <input type="hidden" name="fecha_hora" id="edit_fecha_hora">
    <input type="hidden" name="descuento_venta" id="edit_descuento_venta">
    <input type="hidden" name="total" id="edit_total">
    <input type="hidden" name="total_descuento" id="edit_total_descuento">
    <input type="hidden" name="cobrador_id" id="edit_cobrador_id">
    <input type="hidden" name="plazo" id="edit_plazo">
    <div id="campos-productos-edit"></div>
    <button type="submit" class="btn btn-success btn-lg mt-3" id="btn-guardar-productos" style="display:none;">
        <i class="fa-solid fa-floppy-disk"></i> Guardar Productos
    </button>
</form>

@push('js')
<script src="{{ asset('js/venta-productos.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal cliente
    document.querySelectorAll('.ver-cliente').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var clienteId = this.dataset.clienteId;
            var clienteNombre = this.dataset.clienteNombre;
            
            document.getElementById('modalClienteNombre').textContent = clienteNombre;
            
            const modalCliente = new bootstrap.Modal(document.getElementById('modalCliente'));
            modalCliente.show();
        });
    });

    // Modal de anulación
    document.querySelectorAll('.btn-outline-danger').forEach(button => {
        button.addEventListener('click', function() {
            const ventaId = this.closest('tr').dataset.ventaId;
            const modal = new bootstrap.Modal(document.getElementById('confirmAnulacionModal-' + ventaId));
            modal.show();
        });
    });
});
</script>
@endpush

<!-- Estilos para impresión -->
<style media="print">
    .btn, .breadcrumb, .card-header .d-flex {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-header {
        background: none !important;
        border-bottom: 2px solid #000 !important;
    }
    .table th {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    .badge {
        background-color: #000 !important;
        color: #fff !important;
        border: 1px solid #000 !important;
    }
</style>
@endsection
