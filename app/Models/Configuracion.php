<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Configuracion
 *
 * @property $institucion
 * @property $descripcion
 * @property $objetivos
 * @property $color_1
 * @property $color_2
 * @property $color_3
 * @property $color_4
 * @property $color_5
 * @property $color_6
 * @property $color_7
 * @property $color_8
 * @property $color_9
 * @property $color_10
 * @property $icono
 * @property $logo_principal
 * @property $logo_secundario
 * @property $logo_terciario
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Configuracion extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['institucion', 'descripcion', 'objetivos', 'color_1', 'color_2', 'color_3', 'color_4', 'color_5', 'color_6', 'color_7', 'color_8', 'color_9', 'color_10', 'icono', 'logo_principal', 'logo_secundario', 'logo_terciario'];


}
