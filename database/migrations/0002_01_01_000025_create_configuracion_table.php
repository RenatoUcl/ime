<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->string('institucion');
            $table->text('descripcion');
            $table->text('objetivos');
            $table->string('color_1');
            $table->string('color_2');
            $table->string('color_3');
            $table->string('color_4');
            $table->string('color_5');
            $table->string('color_6');
            $table->string('color_7');
            $table->string('color_8');
            $table->string('color_9');
            $table->string('color_10');
            $table->string('icono');
            $table->string('logo_principal');
            $table->string('logo_secundario');
            $table->string('logo_terciario');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
