<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FASE 0.2 - Corregir FK de respuestas.id_alternativa
 *
 * Problema: La migración original usa foreignId() que crea NOT NULL,
 * pero el código guarda id_alternativa = 0 para respuestas de tipo texto
 * (ResponderController.php:104-105).
 *
 * Solución: Drop FK, cambiar columna a unsignedBigInteger con default 0,
 * recrear FK.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // SQLite no soporta drop foreign key de forma convencional
        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('respuestas', function (Blueprint $table) {
            // Drop FK existente
            try {
                $table->dropForeign(['id_alternativa']);
            } catch (\Throwable $e) {
                // La FK puede no existir con el nombre esperado
            }
        });

        Schema::table('respuestas', function (Blueprint $table) {
            // Cambiar tipo de columna: de foreignId (NOT NULL) a unsignedBigInteger nullable
            $table->unsignedBigInteger('id_alternativa')->nullable()->default(null)->change();
        });

        // Recrear FK
        Schema::table('respuestas', function (Blueprint $table) {
            $table->foreign('id_alternativa')
                ->references('id')
                ->on('alternativas')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        Schema::table('respuestas', function (Blueprint $table) {
            try {
                $table->dropForeign(['id_alternativa']);
            } catch (\Throwable $e) {}
        });

        Schema::table('respuestas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_alternativa')->nullable(false)->change();

            $table->foreign('id_alternativa')
                ->references('id')
                ->on('alternativas');
        });
    }
};
