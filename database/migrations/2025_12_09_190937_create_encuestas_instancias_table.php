<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('encuesta_instancias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_encuesta');
            $table->string('nombre_periodo');
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('id_encuesta')
                  ->references('id')->on('encuestas')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('encuesta_instancias');
    }
};
