<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FASE 0.7 - Corregir nombre de tabla encuesta_instancias -> encuestas_instancias
 *
 * Problema: La migración original (2025_12_09_190937) crea la tabla como
 * `encuesta_instancias` (singular), pero el modelo EncuestaInstancia usa
 * `protected $table = 'encuestas_instancias'` (plural).
 *
 * Esto causa que el modelo no encuentre la tabla.
 *
 * Solución: Renombrar la tabla a plural y actualizar las FKs que la referencian.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // Si la tabla ya tiene el nombre correcto, no hacer nada
        if (Schema::hasTable('encuestas_instancias') && !Schema::hasTable('encuesta_instancias')) {
            return;
        }

        // Si no existe ninguna de las dos tablas, no hacer nada
        if (!Schema::hasTable('encuesta_instancias') && !Schema::hasTable('encuestas_instancias')) {
            return;
        }

        if ($driver === 'sqlite') {
            // SQLite no soporta RENAME TABLE directamente
            // Laravel lo maneja internamente, pero las FKs pueden ser problemáticas
            Schema::rename('encuesta_instancias', 'encuestas_instancias');
            return;
        }

        // Drop FKs que referencian la tabla antes de renombrar
        $tablesConFK = [
            'encuestas_usuarios' => 'id_encuesta_instancia',
            'respuestas' => 'id_encuesta_instancia',
        ];

        foreach ($tablesConFK as $tabla => $columna) {
            if (Schema::hasTable($tabla) && Schema::hasColumn($tabla, $columna)) {
                try {
                    // Buscar el nombre de la FK
                    $fkName = $this->getFkName($tabla, $columna);
                    if ($fkName) {
                        Schema::table($tabla, function (Blueprint $table) use ($fkName) {
                            $table->dropForeign($fkName);
                        });
                    }
                } catch (\Throwable $e) {}
            }
        }

        // Renombrar tabla
        Schema::rename('encuesta_instancias', 'encuestas_instancias');

        // Recrear FKs
        foreach ($tablesConFK as $tabla => $columna) {
            if (Schema::hasTable($tabla) && Schema::hasColumn($tabla, $columna)) {
                try {
                    Schema::table($tabla, function (Blueprint $table) use ($columna) {
                        $table->foreign($columna)
                            ->references('id')
                            ->on('encuestas_instancias')
                            ->onDelete('cascade');
                    });
                } catch (\Throwable $e) {}
            }
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (!Schema::hasTable('encuestas_instancias')) {
            return;
        }

        if ($driver === 'sqlite') {
            Schema::rename('encuestas_instancias', 'encuesta_instancias');
            return;
        }

        // Drop FKs
        $tablesConFK = [
            'encuestas_usuarios' => 'id_encuesta_instancia',
            'respuestas' => 'id_encuesta_instancia',
        ];

        foreach ($tablesConFK as $tabla => $columna) {
            if (Schema::hasTable($tabla) && Schema::hasColumn($tabla, $columna)) {
                try {
                    $fkName = $this->getFkName($tabla, $columna);
                    if ($fkName) {
                        Schema::table($tabla, function (Blueprint $table) use ($fkName) {
                            $table->dropForeign($fkName);
                        });
                    }
                } catch (\Throwable $e) {}
            }
        }

        Schema::rename('encuestas_instancias', 'encuesta_instancias');

        // Recrear FKs apuntando al nombre original
        foreach ($tablesConFK as $tabla => $columna) {
            if (Schema::hasTable($tabla) && Schema::hasColumn($tabla, $columna)) {
                try {
                    Schema::table($tabla, function (Blueprint $table) use ($columna) {
                        $table->foreign($columna)
                            ->references('id')
                            ->on('encuesta_instancias')
                            ->onDelete('cascade');
                    });
                } catch (\Throwable $e) {}
            }
        }
    }

    /**
     * Obtiene el nombre de una foreign key
     */
    private function getFkName(string $table, string $column): ?string
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $result = \DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = DATABASE()
                 AND TABLE_NAME = ?
                 AND COLUMN_NAME = ?
                 AND REFERENCED_TABLE_NAME IS NOT NULL
                 LIMIT 1",
                [$table, $column]
            );

            return $result ? $result[0]->CONSTRAINT_NAME : null;
        }

        if ($driver === 'pgsql') {
            $result = \DB::select(
                "SELECT tc.constraint_name
                 FROM information_schema.table_constraints tc
                 JOIN information_schema.key_column_usage kcu
                   ON tc.constraint_name = kcu.constraint_name
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                 AND tc.table_name = ?
                 AND kcu.column_name = ?
                 LIMIT 1",
                [$table, $column]
            );

            return $result ? $result[0]->constraint_name : null;
        }

        // Para SQLite, retornar null (no se puede drop FK)
        return null;
    }
};
