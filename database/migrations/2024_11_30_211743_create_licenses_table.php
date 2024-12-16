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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            //$table->integer('min_duration_days');
            $table->integer('max_duration_days')->nullable();
            $table->integer('annual_limit')->nullable(); // Ejemplo: 6 días para estudio.
            $table->integer('advance_notice_days')->nullable();
            $table->boolean('requires_justification')->default(false);
            $table->text('required_documents')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
