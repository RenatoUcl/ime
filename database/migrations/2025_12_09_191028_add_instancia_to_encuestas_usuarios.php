<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Agregar columna solo si no existe aún
        if (!Schema::hasColumn('encuestas_usuarios', 'id_instancia')) {
            Schema::table('encuestas_usuarios', function (Blueprint $table) {
                $table->unsignedBigInteger('id_instancia')->after('id_encuesta')->nullable();
            });
        } else {
            // 2. Si ya existe como NOT NULL, cambiar a nullable vía raw SQL
            DB::statement('ALTER TABLE encuestas_usuarios MODIFY COLUMN id_instancia BIGINT UNSIGNED NULL');
        }

        // 3. Limpiar valores inválidos (0 o IDs que no existen en encuesta_instancias)
        DB::table('encuestas_usuarios')
            ->where('id_instancia', 0)
            ->orWhereNotIn('id_instancia', function ($query) {
                $query->select('id')->from('encuesta_instancias');
            })
            ->update(['id_instancia' => null]);

        // 4. Agregar FK solo si no existe
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $table->foreign('id_instancia')
                  ->references('id')->on('encuesta_instancias')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_instancia']);
            $table->dropColumn('id_instancia');
        });
    }
};
