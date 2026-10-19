<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminoVerificacion extends Model
{
    protected $table='terminos_verificacion';

    protected $fillable=[
        'termino',
        'estado'
    ];

    public function subdimensiones()
    {
        return $this->belongsToMany(
        Subdimension::class,
            'subdimension_terminos',
            'id_termino',
            'id_subdimension'
        )
        ->withPivot([
            'peso',
            'obligatorio'
        ]);
    }

}
