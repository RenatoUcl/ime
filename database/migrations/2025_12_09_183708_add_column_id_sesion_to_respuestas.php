<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->foreignId('id_sesion')
                  ->nullable()
                  ->after('id_usuario')
                  ->constrained('encuestas_usuarios')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->dropForeign(['id_sesion']);
            $table->dropColumn('id_sesion');
        });
    }
};
