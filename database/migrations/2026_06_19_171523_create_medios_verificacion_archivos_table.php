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
        Schema::create('medios_verificacion_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_medio_verificacion')
                ->constrained('medios_verificacion')
                ->cascadeOnDelete();
            $table->string('archivo');
            $table->string('nombre_original');
            $table->string('extension',20);
            $table->longText('texto_extraido')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medios_verificacion_archivos');
    }
};
