<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdimension extends Model
{
    protected $table = "subdimensiones";

    protected $perPage = 20;

    protected $fillable = ['id_dimension', 'nombre', 'descripcion', 'posicion', 'estado'];


    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'id_dimension');
    }
    
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'id_subdimension', 'id');
    }
    
}
