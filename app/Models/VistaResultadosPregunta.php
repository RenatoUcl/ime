<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaResultadosPregunta extends Model
{
    protected $table = 'vista_resultados_pregunta';
    public $timestamps = false;

    // Si quieres proteger contra escritura
    protected $guarded = [];
}
