<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaResultadosSubdimension extends Model
{
    protected $table = 'vista_resultados_subdimension';
    public $timestamps = false;

    // Si quieres proteger contra escritura
    protected $guarded = [];
}
