@extends('layouts.app')

@section('title', 'Gestión de Pagos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestión de Pagos</h4>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nuevo Pago
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Módulo de pagos en desarrollo. Próximamente disponible.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

