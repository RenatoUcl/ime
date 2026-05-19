<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'encuestas',
            'preguntas',
            'respuestas',
            'roles',
            'permisos',
            'dimensiones',
            'subdimensiones',
            'alternativas',
            'encuestas_instancias',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->softDeletes();
                });
            }
        }

        if (!Schema::hasTable('auditoria')) {
            Schema::create('auditoria', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('id_usuario')->nullable();
                $t->string('accion');
                $t->string('modelo');
                $t->unsignedBigInteger('modelo_id')->nullable();
                $t->json('datos_anteriores')->nullable();
                $t->json('datos_nuevos')->nullable();
                $t->string('ip_address', 45)->nullable();
                $t->text('user_agent')->nullable();
                $t->timestamps();

                $t->foreign('id_usuario')->references('id')->on('users')->onDelete('set null');
                $t->index(['modelo', 'modelo_id'], 'idx_auditoria_modelo');
                $t->index('id_usuario', 'idx_auditoria_usuario');
                $t->index('created_at', 'idx_auditoria_fecha');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'encuestas',
            'preguntas',
            'respuestas',
            'roles',
            'permisos',
            'dimensiones',
            'subdimensiones',
            'alternativas',
            'encuestas_instancias',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropSoftDeletes();
                });
            }
        }

        Schema::dropIfExists('auditoria');
    }
};
