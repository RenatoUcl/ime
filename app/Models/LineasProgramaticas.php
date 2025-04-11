<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 */
class LineasProgramaticas extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function dimensiones()
    {
        return $this->hasMany(Dimension::class, 'id_linea');
    }
   
}
