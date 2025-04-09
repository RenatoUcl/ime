<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MensajesEstado
 *
 * @property $id
 * @property $nombre
 * @property $created_at
 * @property $updated_at
 *
 * @property Mensaje[] $mensajes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class MensajesEstado extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mensajes()
    {
        return $this->hasMany(\App\Models\Mensaje::class, 'id', 'id_estado');
    }
    
}
