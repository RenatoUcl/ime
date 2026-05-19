<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FASE 0.1 y 0.5 - Consolidación de migraciones duplicadas
 *
 * Problema: Existen 4 migraciones duplicadas que agregan id_encuesta_instancia
 * a encuestas_usuarios y respuestas:
 *   - 2025_12_09_191028_add_instancia_to_encuestas_usuarios
 *   - 2025_12_18_043021_add_instancia_to_encuestas_usuarios_table
 *   - 2025_12_09_191127_add_instancia_to_respuestas
 *   - 2025_12_18_043059_add_instancia_to_respuestas_table
 *
 * Esta migración verifica si las columnas ya existen (por si las duplicadas ya corrieron)
 * y las crea solo si no existen. También agrega columnas faltantes como ultimo_grupo.
 */
return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // ENCUESTAS_USUARIOS
        // -------------------------------------------------------
        Schema::table('encuestas_usuarios', function (Blueprint $table) {

            // Columna id_encuesta_instancia (puede ya existir por migraciones duplicadas)
            if (!Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->unsignedBigInteger('id_encuesta_instancia')
                    ->after('id_encuesta')
                    ->nullable();
            }

            // Columna ultimo_grupo (usada en código pero no creada en migración original)
            if (!Schema::hasColumn('encuestas_usuarios', 'ultimo_grupo')) {
                $table->integer('ultimo_grupo')->default(1)->after('ultima_pregunta_id');
            }

            // Columna id_instancia (compatibilidad - puede ya existir)
            if (!Schema::hasColumn('encuestas_usuarios', 'id_instancia')) {
                $table->unsignedBigInteger('id_instancia')
                    ->after('id_encuesta_instancia')
                    ->nullable();
            }

            // Índice compuesto para búsquedas de reanudación
            if (!Schema::hasIndex('encuestas_usuarios', 'idx_enc_iu_user')) {
                $table->index(
                    ['id_encuesta', 'id_encuesta_instancia', 'id_usuario'],
                    'idx_enc_iu_user'
                );
            }
        });

        // FK separada para evitar conflictos
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $fkExists = $this->fkExists('encuestas_usuarios', 'encuestas_usuarios_id_encuesta_instancia_foreign');

            if (!$fkExists && Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->foreign('id_encuesta_instancia')
                    ->references('id')
                    ->on('encuesta_instancias')
                    ->onDelete('cascade');
            }
        });

        // -------------------------------------------------------
        // RESPUESTAS
        // -------------------------------------------------------
        Schema::table('respuestas', function (Blueprint $table) {

            // Columna id_encuesta_instancia
            if (!Schema::hasColumn('respuestas', 'id_encuesta_instancia')) {
                $table->unsignedBigInteger('id_encuesta_instancia')
                    ->after('id_encuesta')
                    ->nullable();
            }

            // Columna id_instancia (compatibilidad)
            if (!Schema::hasColumn('respuestas', 'id_instancia')) {
                $table->unsignedBigInteger('id_instancia')
                    ->after('id_encuesta_instancia')
                    ->nullable();
            }

            // Unique: una respuesta por (encuesta, instancia, usuario, pregunta)
            if (!Schema::hasIndex('respuestas', 'uq_respuestas_enc_inst_user_preg')) {
                $table->unique(
                    ['id_encuesta', 'id_encuesta_instancia', 'id_usuario', 'id_pregunta'],
                    'uq_respuestas_enc_inst_user_preg'
                );
            }

            // Índice para búsquedas por encuesta+instancia
            if (!Schema::hasIndex('respuestas', 'idx_respuestas_enc_inst')) {
                $table->index(
                    ['id_encuesta', 'id_encuesta_instancia'],
                    'idx_respuestas_enc_inst'
                );
            }
        });

        // FK separada para respuestas
        Schema::table('respuestas', function (Blueprint $table) {
            $fkExists = $this->fkExists('respuestas', 'respuestas_id_encuesta_instancia_foreign');

            if (!$fkExists && Schema::hasColumn('respuestas', 'id_encuesta_instancia')) {
                $table->foreign('id_encuesta_instancia')
                    ->references('id')
                    ->on('encuesta_instancias')
                    ->onDelete('cascade');
            }
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
            if (Schema::hasColumn('respuestas', 'id_instancia')) {
                $table->dropColumn('id_instancia');
            }
        });

        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            try { $table->dropForeign(['id_encuesta_instancia']); } catch (\Throwable $e) {}
            try { $table->dropIndex('idx_enc_iu_user'); } catch (\Throwable $e) {}

            if (Schema::hasColumn('encuestas_usuarios', 'id_encuesta_instancia')) {
                $table->dropColumn('id_encuesta_instancia');
            }
            if (Schema::hasColumn('encuestas_usuarios', 'ultimo_grupo')) {
                $table->dropColumn('ultimo_grupo');
            }
            if (Schema::hasColumn('encuestas_usuarios', 'id_instancia')) {
                $table->dropColumn('id_instancia');
            }
        });
    }

    /**
     * Verifica si una foreign key existe (Laravel no tiene método nativo)
     */
    private function fkExists(string $table, string $fkName): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $result = \DB::select(
                "SELECT COUNT(*) as count FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                 AND TABLE_NAME = ?
                 AND CONSTRAINT_NAME = ?",
                [$table, $fkName]
            );
            return $result[0]->count > 0;
        }

        if ($driver === 'sqlite') {
            // SQLite no soporta drop foreign key, retornamos true para evitar errores
            return true;
        }

        if ($driver === 'pgsql') {
            $result = \DB::select(
                "SELECT COUNT(*) as count FROM information_schema.table_constraints
                 WHERE constraint_name = ? AND table_name = ?",
                [$fkName, $table]
            );
            return $result[0]->count > 0;
        }

        return false;
    }
};
