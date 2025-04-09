<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuestas_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_encuesta')
                ->references('id')
                ->on('encuestas');
            $table->string('nombre');
            $table->string('archivo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuestas_archivos');
    }
};
