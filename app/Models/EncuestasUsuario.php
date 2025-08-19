<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EncuestasUsuario
 *
 * @property $id
 * @property $id_encuesta
 * @property $id_usuario
 * @property $created_at
 * @property $updated_at
 *
 * @property Encuesta $encuesta
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EncuestasUsuario extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_encuesta', 'id_usuario','ultima_pregunta_id','completado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encuesta()
    {
        return $this->belongsTo(\App\Models\Encuesta::class, 'id_encuesta', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }
    
}
