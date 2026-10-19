<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Eliminar FKs que existan (raw SQL porque dropForeign difiere la excepción)
        foreach (['respuestas_id_instancia_foreign', 'respuestas_id_encuesta_usuario_foreign', 'respuestas_id_sesion_foreign'] as $fk) {
            $exists = DB::select(
                "SELECT COUNT(*) as c FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'respuestas' AND CONSTRAINT_NAME = ?",
                [$fk]
            );
            if ($exists[0]->c > 0) {
                DB::statement("ALTER TABLE respuestas DROP FOREIGN KEY `{$fk}`");
            }
        }

        // Eliminar columnas obsoletas (solo si existen)
        Schema::table('respuestas', function (Blueprint $table) {
            foreach (['id_instancia', 'id_encuesta_usuario', 'id_sesion'] as $col) {
                if (Schema::hasColumn('respuestas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down()
    {
        // No se revierten columnas obsoletas
    }
};