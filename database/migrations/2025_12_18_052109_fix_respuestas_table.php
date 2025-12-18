<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('respuestas', function (Blueprint $table) {

            // 🔥 Eliminar FKs primero si existen
            $table->dropForeign(['id_instancia']);
            $table->dropForeign(['id_encuesta_usuario']);
            $table->dropForeign(['id_sesion']);

            // ❌ Eliminar columnas obsoletas
            $table->dropColumn([
                'id_instancia',
                'id_encuesta_usuario',
                'id_sesion',
            ]);
        });
    }

    public function down()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            // No se revierten columnas obsoletas
        });
    }
};