<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dimensiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_linea')
                ->references('id')
                ->on('lineas_programaticas');
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('posicion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dimensiones');
    }
};
