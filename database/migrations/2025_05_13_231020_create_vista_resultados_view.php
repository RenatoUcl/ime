<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW vista_resultados_view AS
            SELECT 
                d.id AS id_dimension,
                d.nombre AS nombre_dimension,
                sd.id AS id_subdimension,
                sd.nombre AS nombre_subdimension,
                r.nivel,
                SUM(r.valor) AS total_valor
            FROM respuestas AS r
            LEFT JOIN subdimensiones AS sd ON sd.id = r.nivel 
            LEFT JOIN dimensiones AS d ON d.id = sd.id_dimension
            GROUP BY r.nivel, d.id, d.nombre, sd.id, sd.nombre
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //DB::statement("DROP VIEW IF EXISTS vista_resultados_view");
        Schema::dropAllViews();
    }
};
