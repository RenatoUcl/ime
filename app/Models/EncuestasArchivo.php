<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EncuestasArchivo
 *
 * @property $id
 * @property $id_encuesta
 * @property $nombre
 * @property $archivo
 * @property $created_at
 * @property $updated_at
 *
 * @property Encuesta $encuesta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EncuestasArchivo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_encuesta', 'nombre', 'archivo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encuesta()
    {
        return $this->belongsTo(\App\Models\Encuesta::class, 'id_encuesta', 'id');
    }
    
}
