<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')
                ->references('id')
                ->on('encuestas');
            $table->foreignId('id_subdimension')
                ->references('id')
                ->on('subdimensiones');
            $table->unsignedInteger('tipo');
            $table->text('texto');
            $table->integer('posicion')->default('0');
            $table->foreignId('id_dependencia')
                ->references('id')
                ->on('preguntas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
