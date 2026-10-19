<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            if (!Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->unsignedBigInteger('id_encuesta_instancia')
                    ->after('id_encuesta')
                    ->nullable();
            } else {
                DB::statement('ALTER TABLE encuestas_usuarios MODIFY COLUMN id_encuesta_instancia BIGINT UNSIGNED NULL');
            }

            // Índice para búsquedas rápidas de reanudación / completado por período
            if (!Schema::hasIndex('encuestas_usuarios', 'idx_enc_iu_user')) {
                $table->index(['id_encuesta', 'id_encuesta_instancia', 'id_usuario'], 'idx_enc_iu_user');
            }
        });

        // Limpiar valores inválidos antes de agregar FK
        DB::table('encuestas_usuarios')
            ->where('id_encuesta_instancia', 0)
            ->orWhereNotIn('id_encuesta_instancia', function ($query) {
                $query->select('id')->from('encuesta_instancias');
            })
            ->update(['id_encuesta_instancia' => null]);

        // FK (en una segunda llamada para evitar conflictos en algunos motores)
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $table->foreign('id_encuesta_instancia')
                ->references('id')->on('encuesta_instancias')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            try { $table->dropForeign(['id_encuesta_instancia']); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_enc_iu_user'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->dropColumn('id_encuesta_instancia');
            }
        });
    }
};