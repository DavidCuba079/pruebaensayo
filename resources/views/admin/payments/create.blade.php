@extends('layouts.app')

@section('title', 'Nuevo Pago')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Nuevo Pago</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Formulario de pagos en desarrollo. Próximamente disponible.
                    </div>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

