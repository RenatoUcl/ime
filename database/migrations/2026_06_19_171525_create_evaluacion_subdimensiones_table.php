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
        Schema::create('evaluacion_subdimensiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')
                ->constrained('encuestas');
            $table->foreignId('id_subdimension')
                ->constrained('subdimensiones');
            $table->decimal('puntaje_obtenido',8,2);
            $table->decimal('puntaje_maximo',8,2);
            $table->decimal('porcentaje',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_subdimensiones');
    }
};
