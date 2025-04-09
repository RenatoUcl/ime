<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario_origen')
                ->references('id')
                ->on('users');
            $table->foreignId('id_usuario_destino')
                ->references('id')
                ->on('users');
            $table->foreignId('id_estado')
                ->references('id')
                ->on('mensajes_estados');
            $table->string('asunto');
            $table->text('mensaje');
            $table->integer('leido');
            $table->datetime('readed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
