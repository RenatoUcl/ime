<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $table->integer('ultimo_grupo')->default(1)->after('completado');
        });
    }

    public function down(): void
    {
        Schema::table('encuestas_usuarios', function (Blueprint $table) {
            $table->dropColumn('ultimo_grupo');
        });
    }
};