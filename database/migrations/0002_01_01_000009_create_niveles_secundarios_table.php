<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles_secundarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nivel_primario')
                ->references('id')
                ->on('niveles_primarios');
            $table->foreignId('id_rol')
                ->references('id')
                ->on('roles');
            $table->string('nombre');
            $table->text('descripcion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles_secundarios');
    }
};
