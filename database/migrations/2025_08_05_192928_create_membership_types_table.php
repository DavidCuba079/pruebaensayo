<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_days')->comment('Duración en días de la membresía');
            $table->decimal('price', 10, 2)->comment('Precio regular');
            $table->decimal('discount_price', 10, 2)->nullable()->comment('Precio con descuento si aplica');
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable()->comment('Características de la membresía en formato JSON');
            $table->integer('classes_allowed')->default(0)->comment('Número de clases incluidas (0 = ilimitado)');
            $table->integer('max_entries_per_day')->default(1)->comment('Máximo de ingresos por día');
            $table->integer('max_entries_per_week')->default(7)->comment('Máximo de ingresos por semana');
            $table->boolean('can_freeze')->default(false)->comment('Si la membresía puede ser congelada');
            $table->integer('freeze_days_allowed')->default(0)->comment('Días máximos de congelación permitidos');
            $table->boolean('requires_medical_certificate')->default(false)->comment('Si requiere certificado médico');
            $table->boolean('requires_contract')->default(false)->comment('Si requiere contrato firmado');
            $table->string('contract_file')->nullable()->comment('Ruta al archivo de contrato si aplica');
            $table->integer('order')->default(0)->comment('Orden de visualización');
            $table->softDeletes();
            $table->timestamps();

            // Índices para mejorar el rendimiento de las consultas
            $table->index('is_active');
            $table->index('price');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
