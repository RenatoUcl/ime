<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pregunta')
                ->references('id')
                ->on('preguntas');
            $table->text('texto');
            $table->integer('valor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternativas');
    }
};
