<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CabeceraAlternativa
 *
 * @property $id
 * @property $id_cabecera
 * @property $pregunta
 * @property $orden
 * @property $created_at
 * @property $updated_at
 *
 * @property CabeceraPregunta $cabeceraPregunta
 * @property CabeceraRespuesta[] $cabeceraRespuestas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CabeceraAlternativa extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_cabecera', 'pregunta', 'orden'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cabeceraPregunta()
    {
        return $this->belongsTo(\App\Models\CabeceraPregunta::class, 'id_cabecera', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cabeceraRespuestas()
    {
        return $this->hasMany(\App\Models\CabeceraRespuesta::class, 'id', 'id_alternativa');
    }
    
}
