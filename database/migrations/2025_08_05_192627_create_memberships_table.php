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
        // Primero creamos la tabla sin las claves foráneas
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->unsignedBigInteger('membership_type_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'bank_transfer', 'other'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'overdue', 'refunded', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Índices para mejorar el rendimiento de las consultas
            $table->index(['member_id', 'status']);
            $table->index('end_date');
            $table->index('payment_status');
        });

        // Luego añadimos las claves foráneas en una migración separada
        Schema::table('memberships', function (Blueprint $table) {
            // Asegurarse de que las tablas referenciadas existen
            if (Schema::hasTable('members')) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            }

            if (Schema::hasTable('membership_types')) {
                $table->foreign('membership_type_id')
                    ->references('id')
                    ->on('membership_types')
                    ->onDelete('set null');
            }

            if (Schema::hasTable('users')) {
                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
