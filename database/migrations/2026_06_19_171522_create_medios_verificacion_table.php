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
        Schema::create('medios_verificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')->constrained('encuestas');
            $table->foreignId('id_dimension')->constrained('dimensiones');
            $table->foreignId('id_subdimension')->constrained('subdimensiones');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('usuario_carga')->constrained('users');
            $table->enum('estado',[
                    'pendiente',
                    'analizando',
                    'analizado',
                    'aprobado',
                    'rechazado',
                    'revision'
                ])->default('pendiente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medios_verificacion');
    }
};
