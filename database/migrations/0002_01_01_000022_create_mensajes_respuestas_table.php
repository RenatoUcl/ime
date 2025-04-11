<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mensaje')
                ->references('id')
                ->on('mensajes');
            $table->foreignId('id_usuario')
                ->references('id')
                ->on('users');
            $table->text('respuesta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes_respuestas');
    }
};
