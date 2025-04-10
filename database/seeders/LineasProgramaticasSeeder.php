<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LineasProgramaticas;

class LineasProgramaticasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lineas = [
            ['nombre' => 'Educación', 'descripcion' => 'Línea Educación'],
            ['nombre' => 'Salud', 'descripcion' => 'Línea Salud'],
            ['nombre' => 'Investigación', 'descripcion' => 'Línea Investigación'],
        ];

        foreach ($lineas as $linea) {
            LineasProgramaticas::create($linea);
        }
    }
}
