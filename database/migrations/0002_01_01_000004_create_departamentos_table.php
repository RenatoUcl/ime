<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
        Schema::create('departamenos_usuarios', function (Blueprint $table) {
            $table->foreignId('id_depto')
                ->references('id')
                ->on('departamentos');
            $table->foreignId('id_usuario')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
