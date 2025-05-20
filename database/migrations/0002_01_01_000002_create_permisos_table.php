<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
        Schema::create('permisos_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rol')
                  ->constrained('roles')
                  ->onDelete('cascade');
            $table->foreignId('id_permiso')
                  ->constrained('permisos')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_rol', 'id_permiso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permisos_roles');
        Schema::dropIfExists('permisos');
    }
};
