<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioVerificacion extends Model
{
    protected $table='medios_verificacion';

    protected $fillable=[
        'id_encuesta',
        'id_dimension',
        'id_subdimension',
        'titulo',
        'descripcion',
        'usuario_carga',
        'estado'
    ];

    public function archivos()
    {
        return $this->hasMany(
            MedioVerificacionArchivo::class,
            'id_medio_verificacion'
        );
    }

    public function subdimension()
    {
        return $this->belongsTo(
            Subdimension::class,
            'id_subdimension'
        );
    }
}
