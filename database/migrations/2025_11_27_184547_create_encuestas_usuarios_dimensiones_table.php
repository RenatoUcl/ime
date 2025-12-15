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
        Schema::create('encuestas_usuarios_dimensiones', function (Blueprint $table) {
            $table->id();

            // Usuario que podrá responder la dimensión
            $table->foreignId('id_usuario')
                ->constrained('users')       // tabla users
                ->cascadeOnDelete();

            // Encuesta correspondiente
            $table->foreignId('id_encuesta')
                ->constrained('encuestas')   // tabla encuestas
                ->cascadeOnDelete();

            // Dimensión asignada
            $table->foreignId('id_dimension')
                ->constrained('dimensiones') // tabla dimensiones
                ->cascadeOnDelete();

            // Orden en que se deben mostrar las dimensiones al usuario
            $table->integer('orden')->default(1);

            // Activado/desactivado
            $table->tinyInteger('estado')->default(1);

            $table->timestamps();

            // Evitar duplicaciones: un usuario no puede repetir la misma dimensión en la misma encuesta
            $table->unique(['id_usuario', 'id_encuesta', 'id_dimension'], 'uq_usuario_encuesta_dimension');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas_usuarios_dimensiones');
    }
};
