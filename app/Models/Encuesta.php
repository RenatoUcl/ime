<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Encuesta
 *
 * @property $id
 * @property $id_linea
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property CabeceraPregunta[] $cabeceraPreguntas
 * @property EncuestasArchivo[] $encuestasArchivos
 * @property EncuestasUsuario[] $encuestasUsuarios
 * @property Pregunta[] $preguntas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Encuesta extends Model
{    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_linea', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cabeceraPreguntas()
    {
        return $this->hasMany(\App\Models\CabeceraPregunta::class, 'id', 'id_encuesta');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encuestasArchivos()
    {
        return $this->hasMany(\App\Models\EncuestasArchivo::class, 'id', 'id_encuesta');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encuestasUsuarios()
    {
        return $this->hasMany(\App\Models\EncuestasUsuario::class, 'id', 'id_encuesta');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preguntas()
    {
        return $this->hasMany(\App\Models\Pregunta::class, 'id_encuesta');
    }

    public function linea()
    {
        return $this->belongsTo(LineasProgramaticas::class, 'id_linea');
    }
    
}
