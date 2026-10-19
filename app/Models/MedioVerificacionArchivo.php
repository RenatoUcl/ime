<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioVerificacionArchivo extends Model
{
    protected $table='medios_verificacion_archivos';

    protected $fillable=[
        'id_medio_verificacion',
        'archivo',
        'nombre_original',
        'extension',
        'texto_extraido'
    ];

    public function analisis()
    {
        return $this->hasOne(
            MedioVerificacionAnalisis::class,
            'id_archivo'
        );
    }

    public function medioVerificacion()
    {
        return $this->belongsTo(
            MedioVerificacion::class,
            'id_medio_verificacion'
        );
    }
}
