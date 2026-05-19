<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FASE 0.3 + 0.6 - Agregar índices faltantes en columnas frecuentemente consultadas
 *
 * Problema: No hay índices en columnas que se consultan frecuentemente:
 *   - preguntas.id_encuesta (usado en TODAS las consultas de preguntas)
 *   - preguntas.id_subdimension (join con subdimensiones)
 *   - alternativas.id_pregunta (join con preguntas)
 *   - respuestas.id_usuario (conteo de usuarios)
 *   - respuestas.id_encuesta (filtrado por encuesta)
 *   - respuestas.id_encuesta_instancia (filtrado por período)
 *   - encuestas_usuarios.id_encuesta
 *   - encuestas_usuarios.id_usuario
 *   - encuestas.id_linea
 *   - dimensiones.id_linea
 *   - subdimensiones.id_dimension
 *
 * Impacto: Solo performance, sin riesgo de romper código existente.
 */
return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // PREGUNTAS
        // -------------------------------------------------------
        Schema::table('preguntas', function (Blueprint $table) {
            if (!Schema::hasIndex('preguntas', 'idx_preguntas_encuesta')) {
                $table->index('id_encuesta', 'idx_preguntas_encuesta');
            }

            if (!Schema::hasIndex('preguntas', 'idx_preguntas_subdimension')) {
                $table->index('id_subdimension', 'idx_preguntas_subdimension');
            }

            if (!Schema::hasIndex('preguntas', 'idx_preguntas_dependencia')) {
                $table->index('id_dependencia', 'idx_preguntas_dependencia');
            }

            // Índice compuesto para búsqueda de preguntas por encuesta + subdimensión
            if (!Schema::hasIndex('preguntas', 'idx_preguntas_encuesta_subdim')) {
                $table->index(['id_encuesta', 'id_subdimension'], 'idx_preguntas_encuesta_subdim');
            }
        });

        // -------------------------------------------------------
        // ALTERNATIVAS
        // -------------------------------------------------------
        Schema::table('alternativas', function (Blueprint $table) {
            if (!Schema::hasIndex('alternativas', 'idx_alternativas_pregunta')) {
                $table->index('id_pregunta', 'idx_alternativas_pregunta');
            }

            if (!Schema::hasIndex('alternativas', 'idx_alternativas_dependencia')) {
                $table->index('id_dependencia', 'idx_alternativas_dependencia');
            }
        });

        // -------------------------------------------------------
        // RESPUESTAS
        // -------------------------------------------------------
        Schema::table('respuestas', function (Blueprint $table) {
            if (!Schema::hasIndex('respuestas', 'idx_respuestas_usuario')) {
                $table->index('id_usuario', 'idx_respuestas_usuario');
            }

            if (!Schema::hasIndex('respuestas', 'idx_respuestas_pregunta')) {
                $table->index('id_pregunta', 'idx_respuestas_pregunta');
            }

            // Índice compuesto para conteo de usuarios por encuesta+instancia
            if (!Schema::hasIndex('respuestas', 'idx_respuestas_encuesta_usuarios')) {
                $table->index(
                    ['id_encuesta', 'id_encuesta_instancia', 'id_usuario'],
                    'idx_respuestas_encuesta_usuarios'
                );
            }
        });

        // -------------------------------------------------------
        // ENCUESTAS_USUARIOS
        // -------------------------------------------------------
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            if (!Schema::hasIndex('encuestas_usuarios', 'idx_eu_encuesta')) {
                $table->index('id_encuesta', 'idx_eu_encuesta');
            }

            if (!Schema::hasIndex('encuestas_usuarios', 'idx_eu_usuario')) {
                $table->index('id_usuario', 'idx_eu_usuario');
            }
        });

        // -------------------------------------------------------
        // ENCUESTAS
        // -------------------------------------------------------
        Schema::table('encuestas', function (Blueprint $table) {
            if (!Schema::hasIndex('encuestas', 'idx_encuestas_linea')) {
                $table->index('id_linea', 'idx_encuestas_linea');
            }
        });

        // -------------------------------------------------------
        // DIMENSIONES
        // -------------------------------------------------------
        Schema::table('dimensiones', function (Blueprint $table) {
            if (!Schema::hasIndex('dimensiones', 'idx_dimensiones_linea')) {
                $table->index('id_linea', 'idx_dimensiones_linea');
            }
        });

        // -------------------------------------------------------
        // SUBDIMENSIONES
        // -------------------------------------------------------
        Schema::table('subdimensiones', function (Blueprint $table) {
            if (!Schema::hasIndex('subdimensiones', 'idx_subdimensiones_dimension')) {
                $table->index('id_dimension', 'idx_subdimensiones_dimension');
            }
        });

        // -------------------------------------------------------
        // ENCUESTA_INSTANCIAS
        // -------------------------------------------------------
        Schema::table('encuesta_instancias', function (Blueprint $table) {
            if (!Schema::hasIndex('encuesta_instancias', 'idx_instancias_encuesta')) {
                $table->index('id_encuesta', 'idx_instancias_encuesta');
            }

            // Índice para búsqueda de período activo por fecha
            if (!Schema::hasIndex('encuesta_instancias', 'idx_instancias_fechas')) {
                $table->index(
                    ['id_encuesta', 'estado', 'fecha_desde', 'fecha_hasta'],
                    'idx_instancias_fechas'
                );
            }
        });

        // -------------------------------------------------------
        // ENCUESTAS_USUARIOS_DIMENSIONES
        // -------------------------------------------------------
        Schema::table('encuestas_usuarios_dimensiones', function (Blueprint $table) {
            if (!Schema::hasIndex('encuestas_usuarios_dimensiones', 'idx_eud_usuario_encuesta')) {
                $table->index(
                    ['id_usuario', 'id_encuesta'],
                    'idx_eud_usuario_encuesta'
                );
            }
        });

        // -------------------------------------------------------
        // MENSAJES
        // -------------------------------------------------------
        Schema::table('mensajes', function (Blueprint $table) {
            if (!Schema::hasIndex('mensajes', 'idx_mensajes_destino')) {
                $table->index('id_usuario_destino', 'idx_mensajes_destino');
            }

            if (!Schema::hasIndex('mensajes', 'idx_mensajes_estado')) {
                $table->index('id_estado', 'idx_mensajes_estado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mensajes', function (Blueprint $table) {
            try { $table->dropIndex('idx_mensajes_destino'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_mensajes_estado'); } catch (\Throwable $e) {}
        });

        Schema::table('encuestas_usuarios_dimensiones', function (Blueprint $table) {
            try { $table->dropIndex('idx_eud_usuario_encuesta'); } catch (\Throwable $e) {}
        });

        Schema::table('encuesta_instancias', function (Blueprint $table) {
            try { $table->dropIndex('idx_instancias_encuesta'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_instancias_fechas'); } catch (\Throwable $e) {}
        });

        Schema::table('subdimensiones', function (Blueprint $table) {
            try { $table->dropIndex('idx_subdimensiones_dimension'); } catch (\Throwable $e) {}
        });

        Schema::table('dimensiones', function (Blueprint $table) {
            try { $table->dropIndex('idx_dimensiones_linea'); } catch (\Throwable $e) {}
        });

        Schema::table('encuestas', function (Blueprint $table) {
            try { $table->dropIndex('idx_encuestas_linea'); } catch (\Throwable $e) {}
        });

        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            try { $table->dropIndex('idx_eu_encuesta'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_eu_usuario'); } catch (\Throwable $e) {}
        });

        Schema::table('respuestas', function (Blueprint $table) {
            try { $table->dropIndex('idx_respuestas_usuario'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_respuestas_pregunta'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_respuestas_encuesta_usuarios'); } catch (\Throwable $e) {}
        });

        Schema::table('alternativas', function (Blueprint $table) {
            try { $table->dropIndex('idx_alternativas_pregunta'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_alternativas_dependencia'); } catch (\Throwable $e) {}
        });

        Schema::table('preguntas', function (Blueprint $table) {
            try { $table->dropIndex('idx_preguntas_encuesta'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_preguntas_subdimension'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_preguntas_dependencia'); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_preguntas_encuesta_subdim'); } catch (\Throwable $e) {}
        });
    }
};
