<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CabeceraRespuesta
 *
 * @property $id
 * @property $id_pregunta
 * @property $id_alternativa
 * @property $respuesta
 * @property $created_at
 * @property $updated_at
 *
 * @property CabeceraAlternativa $cabeceraAlternativa
 * @property CabeceraPregunta $cabeceraPregunta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CabeceraRespuesta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_pregunta', 'id_alternativa', 'respuesta'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cabeceraAlternativa()
    {
        return $this->belongsTo(\App\Models\CabeceraAlternativa::class, 'id_alternativa', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cabeceraPregunta()
    {
        return $this->belongsTo(\App\Models\CabeceraPregunta::class, 'id_pregunta', 'id');
    }
    
}
