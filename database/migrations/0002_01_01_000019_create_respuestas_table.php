<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pregunta')
                ->references('id')
                ->on('preguntas');
            $table->foreignId('id_alternativa')
                ->references('id')
                ->on('alternativas');
            $table->integer('valor');
            $table->integer('nivel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
