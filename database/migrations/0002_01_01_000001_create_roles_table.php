<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('roles_usuarios', function (Blueprint $table) {
            $table->foreignId('id_user')
                ->references('id')
                ->on('roles')
                ->unique();
            $table->foreignId('id_rol')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('roles_usuarios');
    }
};
