<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_instancia')->after('id_encuesta');

            $table->foreign('id_instancia')
                  ->references('id')->on('encuesta_instancias')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->dropForeign(['id_instancia']);
            $table->dropColumn('id_instancia');
        });
    }
};
