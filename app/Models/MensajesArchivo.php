<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MensajesArchivo
 *
 * @property $id
 * @property $id_mensaje
 * @property $id_usuario
 * @property $nombre
 * @property $archivo
 * @property $created_at
 * @property $updated_at
 *
 * @property Mensaje $mensaje
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MensajesArchivo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_mensaje', 'id_usuario', 'nombre', 'archivo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mensaje()
    {
        return $this->belongsTo(\App\Models\Mensaje::class, 'id_mensaje', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }
    
}
