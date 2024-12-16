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
        Schema::create('license_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained();
            $table->foreignId('employee_id')->constrained('users');
            $table->foreignId('supervisor_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days')->nullable();
            $table->enum('status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->string('justification_file')->nullable();
            $table->text('remarks')->nullable(); // Comentarios del supervisor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_requests');
    }
};
