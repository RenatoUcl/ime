<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuestas_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')
                ->references('id')
                ->on('encuestas')
                ->unique();
            $table->foreignId('id_usuario')
                ->references('id')
                ->on('users')
                ->unique();
            $table->unsignedBigInteger('ultima_pregunta_id')->nullable();
            $table->boolean('completado')->default(false);    
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuestas_usuarios');
    }
};
