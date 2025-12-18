<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->unsignedBigInteger('id_encuesta_instancia')
                    ->after('id_encuesta');
            }

            // Índice para búsquedas rápidas de reanudación / completado por período
            $table->index(['id_encuesta', 'id_encuesta_instancia', 'id_usuario'], 'idx_enc_iu_user');
        });

        // FK (en una segunda llamada para evitar conflictos en algunos motores)
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            // Si no existe la FK, la creamos
            // Nota: Laravel no trae "hasForeign" nativo, así que asumimos que no existe.
            $table->foreign('id_encuesta_instancia')
                ->references('id')->on('encuesta_instancias')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            // El orden importa: primero FK, luego índice, luego columna
            try { $table->dropForeign(['id_encuesta_instancia']); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_enc_iu_user'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->dropColumn('id_encuesta_instancia');
            }
        });
    }
};