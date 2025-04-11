<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabecera_alternativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cabecera')
                ->references('id')
                ->on('cabecera_preguntas');
            $table->text('pregunta');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabecera_alternativas');
    }
};
