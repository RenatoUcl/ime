<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasColumn('respuestas', 'id_instancia')) {
            Schema::table('respuestas', function (Blueprint $table) {
                $table->unsignedBigInteger('id_instancia')->after('id_encuesta')->nullable();
            });
        }

        DB::table('respuestas')
            ->where('id_instancia', 0)
            ->orWhereNotIn('id_instancia', function ($query) {
                $query->select('id')->from('encuesta_instancias');
            })
            ->update(['id_instancia' => null]);

        Schema::table('respuestas', function (Blueprint $table) {
            $table->foreign('id_instancia')
                  ->references('id')->on('encuesta_instancias')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->dropForeign(['id_instancia']);
            $table->dropColumn('id_instancia');
        });
    }
};
