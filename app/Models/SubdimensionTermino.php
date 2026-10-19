<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubdimensionTermino extends Model
{
    protected $table='subdimension_terminos';
    protected $fillable=[
        'id_subdimension',
        'id_termino',
        'peso',
        'obligatorio'
    ];
}
