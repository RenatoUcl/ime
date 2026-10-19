<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FASE 0.4 - Corregir tabla roles_usuarios para permitir multi-rol
 *
 * Problema: La migración original (0002_01_01_000001) define unique() en id_user,
 * lo que impide que un usuario tenga múltiples roles. El código usa sync()
 * asumiendo que sí es posible.
 *
 * Solución: Quitar unique de id_user, agregar unique compuesto (id_user, id_rol),
 * y agregar primary key para la tabla pivote.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('roles_usuarios', function (Blueprint $table) use ($driver) {

            // Agregar ID como primary key si no existe
            if (!Schema::hasColumn('roles_usuarios', 'id')) {
                $table->id()->first();
            }

            // Quitar FK existente para poder modificar unique
            if ($driver !== 'sqlite') {
                foreach (['id_user', 'id_rol'] as $fkName) {
                    $exists = \DB::select(
                        "SELECT COUNT(*) as c FROM information_schema.KEY_COLUMN_USAGE
                         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'roles_usuarios' AND CONSTRAINT_NAME = ?",
                        [$fkName]
                    );
                    if ($exists[0]->c > 0) {
                        \DB::statement("ALTER TABLE roles_usuarios DROP FOREIGN KEY `{$fkName}`");
                    }
                }
            }

            // Quitar unique de id_user - buscar dinámicamente el nombre del índice
            $indexes = Schema::getIndexes('roles_usuarios');
            foreach ($indexes as $index) {
                $indexName = is_array($index) ? $index['name'] : $index->name;
                $columns = is_array($index) ? $index['columns'] : ($index->columns ?? []);
                if (in_array('id_user', $columns) && count($columns) === 1 && Schema::hasIndex('roles_usuarios', $indexName)) {
                    $table->dropUnique($indexName);
                    break;
                }
            }

            // Agregar unique compuesto (id_user, id_rol) para evitar duplicados
            // pero permitir múltiples roles por usuario
            if (!Schema::hasIndex('roles_usuarios', 'uq_roles_usuarios_user_rol')) {
                $table->unique(['id_user', 'id_rol'], 'uq_roles_usuarios_user_rol');
            }

            // Recrear FKs
            if ($driver !== 'sqlite') {
                $table->foreign('id_user')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->foreign('id_rol')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite') {
            foreach (['id_user', 'id_rol'] as $fkName) {
                $exists = \DB::select(
                    "SELECT COUNT(*) as c FROM information_schema.KEY_COLUMN_USAGE
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'roles_usuarios' AND CONSTRAINT_NAME = ?",
                    [$fkName]
                );
                if ($exists[0]->c > 0) {
                    \DB::statement("ALTER TABLE roles_usuarios DROP FOREIGN KEY `{$fkName}`");
                }
            }
        }

        Schema::table('roles_usuarios', function (Blueprint $table) use ($driver) {
            if (Schema::hasIndex('roles_usuarios', 'uq_roles_usuarios_user_rol')) {
                $table->dropUnique('uq_roles_usuarios_user_rol');
            }

            // Restaurar unique en id_user
            $table->unique('id_user');

            if ($driver !== 'sqlite') {
                $table->foreign('id_user')
                    ->references('id')
                    ->on('users');

                $table->foreign('id_rol')
                    ->references('id')
                    ->on('roles');
            }
        });
    }
};
