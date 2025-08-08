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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Código único del socio');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dni')->unique()->comment('Documento Nacional de Identidad');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_phone')->nullable()->comment('Teléfono de contacto de emergencia');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('occupation')->nullable();
            $table->text('notes')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('status')->default(true)->comment('Estado del socio (activo/inactivo)');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('Usuario asociado al socio');
            $table->softDeletes();
            $table->timestamps();
        });

        // Índices para búsquedas rápidas
        Schema::table('members', function (Blueprint $table) {
            $table->index(['first_name', 'last_name']);
            $table->index('dni');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
