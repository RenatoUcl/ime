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
        Schema::create('medios_verificacion_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_archivo')
                ->constrained('medios_verificacion_archivos')
                ->cascadeOnDelete();
            $table->json('terminos_encontrados')
                ->nullable();
            $table->json('terminos_no_encontrados')
                ->nullable();
            $table->decimal('puntaje',5,2)
                ->default(0);
            $table->string('resultado')
                ->nullable();
            $table->text('observacion')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medios_verificacion_analisis');
    }
};
