<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {

            // 1. Eliminar FOREIGN KEY
            if (Schema::hasColumn('encuestas_usuarios', 'id_instancia')) {
                $table->dropForeign('encuestas_usuarios_id_instancia_foreign');
            }

            // 2. Eliminar columna
            if (Schema::hasColumn('encuestas_usuarios', 'id_instancia')) {
                $table->dropColumn('id_instancia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {

            $table->unsignedBigInteger('id_instancia')->after('id_encuesta_instancia');

            // ⚠️ Ajusta la tabla referenciada si era distinta
            $table->foreign('id_instancia')
                  ->references('id')
                  ->on('encuestas_instancias')
                  ->onDelete('cascade');
        });
    }
};