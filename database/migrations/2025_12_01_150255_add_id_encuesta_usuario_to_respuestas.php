<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->foreignId('id_encuesta_usuario')
                  ->nullable()
                  ->after('id_alternativa')
                  ->constrained('encuestas_usuarios')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->dropForeign(['id_encuesta_usuario']);
            $table->dropColumn('id_encuesta_usuario');
        });
    }
};
