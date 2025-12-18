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
    protected $table = 'encuestas_usuarios';
    
    protected $perPage = 20;

    protected $fillable = [
        'id_encuesta', 
        'id_encuesta_instancia',
        'id_usuario',
        'ultima_pregunta_id',
        'ultimo_grupo',
        'completado'
    ];

    public function encuesta()
    {
        return $this->belongsTo(\App\Models\Encuesta::class, 'id_encuesta', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }
    
}
