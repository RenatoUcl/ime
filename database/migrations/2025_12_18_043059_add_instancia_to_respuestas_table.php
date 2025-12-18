<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            if (!Schema::hasColumn('respuestas', 'id_encuesta_instancia')) {
                $table->unsignedBigInteger('id_encuesta_instancia')
                    ->after('id_encuesta');
            }

            // ÚNICO: una respuesta por (encuesta, período, usuario, pregunta)
            // Esto evita duplicados incluso si algo falla en el frontend.
            $table->unique(
                ['id_encuesta', 'id_encuesta_instancia', 'id_usuario', 'id_pregunta'],
                'uq_respuestas_enc_inst_user_preg'
            );

            $table->index(['id_encuesta', 'id_encuesta_instancia'], 'idx_respuestas_enc_inst');
        });

        Schema::table('respuestas', function (Blueprint $table) {
            $table->foreign('id_encuesta_instancia')
                ->references('id')->on('encuestas_instancias')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            try { $table->dropForeign(['id_encuesta_instancia']); } catch (\Throwable $e) {}
            try { $table->dropUnique('uq_respuestas_enc_inst_user_preg'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_respuestas_enc_inst'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('respuestas', 'id_encuesta_instancia')) {
                $table->dropColumn('id_encuesta_instancia');
            }
        });
    }
};