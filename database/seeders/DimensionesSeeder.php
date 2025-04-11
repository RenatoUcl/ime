<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dimension;
use App\Models\LineasProgramaticas;

class DimensionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nombres = [
            'Trayectoria',
            'Modelos de intervención',
            'Autonomía de los equipos',
            'Confianza funcional',
            'Condiciones básicas de operación',
            'Coordinaciones territoriales',
            'Coordinación funcional',
            'Sistemas de regulación',
        ];

        $lineas = LineasProgramaticas::all();

        foreach ($lineas as $linea) {
            foreach ($nombres as $nombre) {
                Dimension::create([
                    'id_linea' => $linea->id,
                    'nombre' => $nombre,
                    'descripcion' => "Dimensión $nombre para línea {$linea->nombre}",
                ]);
            }
        }
    }
}
