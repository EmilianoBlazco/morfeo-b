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
        Schema::create('justify_uploads', function (Blueprint $table) {
            $table->id();

            // Clave foránea al empleado
            $table->foreignId('employee_id')
                ->constrained('users');

            // Clave foránea al supervisor
            $table->foreignId('supervisor_id')
                ->nullable()
                ->constrained('users');

            // Clave foránea a la asistencia
            $table->foreignId('attendance_id')
                ->constrained();

            // Ruta del archivo
            $table->string('file_path');

            // Estado de la justificación
            $table->enum('status', ['Aceptado', 'Rechazado', 'Pendiente'])
                ->default('Pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justify_uploads');
    }
};
