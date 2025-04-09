<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabecera_preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')
                ->references('id')
                ->on('encuestas');
            $table->boolean('tipo');
            $table->text('pregunta');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabecera_preguntas');
    }
};
