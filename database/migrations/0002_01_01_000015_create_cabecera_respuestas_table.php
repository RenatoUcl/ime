<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabecera_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pregunta')
                ->references('id')
                ->on('cabecera_preguntas');
            $table->foreignId('id_alternativa')
                ->references('id')
                ->on('cabecera_alternativas');
            $table->text('respuesta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabecera_respuestas');
    }
};
