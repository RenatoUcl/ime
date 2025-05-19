<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaResultadosDimension extends Model
{
    protected $table = 'vista_resultados_view';
    public $timestamps = false;

    // Si quieres proteger contra escritura
    protected $guarded = [];
}
