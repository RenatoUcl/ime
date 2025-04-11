<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Mensaje
 *
 * @property $id
 * @property $id_usuario_origen
 * @property $id_usuario_destino
 * @property $id_estado
 * @property $asunto
 * @property $mensaje
 * @property $leido
 * @property $readed_at
 * @property $created_at
 * @property $updated_at
 *
 * @property MensajesEstado $mensajesEstado
 * @property User $user
 * @property User $user
 * @property MensajesArchivo[] $mensajesArchivos
 * @property MensajesRespuesta[] $mensajesRespuestas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Mensaje extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_usuario_origen', 'id_usuario_destino', 'id_estado', 'asunto', 'mensaje', 'leido', 'readed_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mensajesEstado()
    {
        return $this->belongsTo(\App\Models\MensajesEstado::class, 'id_estado', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario_destino', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario_origen', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mensajesArchivos()
    {
        return $this->hasMany(\App\Models\MensajesArchivo::class, 'id', 'id_mensaje');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mensajesRespuestas()
    {
        return $this->hasMany(\App\Models\MensajesRespuesta::class, 'id', 'id_mensaje');
    }
    
}
