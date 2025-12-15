<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EncuestaUsuarioDimension extends Model
{
    use HasFactory;

    protected $table = 'encuestas_usuarios_dimensiones';

    protected $fillable = [
        'id_usuario',
        'id_encuesta',
        'id_dimension',
        'orden',
        'estado',
    ];

    /**
     * Relaciones
     */

    // Usuario asignado
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Encuesta a la cual pertenece
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta');
    }

    // Dimensión asignada al usuario
    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'id_dimension');
    }
}
