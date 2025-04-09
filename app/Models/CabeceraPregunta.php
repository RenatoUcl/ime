<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CabeceraPregunta
 *
 * @property $id
 * @property $id_encuesta
 * @property $tipo
 * @property $pregunta
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Encuesta $encuesta
 * @property CabeceraAlternativa[] $cabeceraAlternativas
 * @property CabeceraRespuesta[] $cabeceraRespuestas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CabeceraPregunta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_encuesta', 'tipo', 'pregunta', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encuesta()
    {
        return $this->belongsTo(\App\Models\Encuesta::class, 'id_encuesta', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cabeceraAlternativas()
    {
        return $this->hasMany(\App\Models\CabeceraAlternativa::class, 'id', 'id_cabecera');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cabeceraRespuestas()
    {
        return $this->hasMany(\App\Models\CabeceraRespuesta::class, 'id', 'id_pregunta');
    }
    
}
