<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'admin', 'descripcion' => 'Administrador del sistema'],
            ['nombre' => 'director', 'descripcion' => 'Directores'],
            ['nombre' => 'coordinador', 'descripcion' => 'Coordinadores de Area y/o Equipos'],
            ['nombre' => 'equipo', 'descripcion' => 'Personal de Colaboracion Administrativos Asistentes'],
        ];

        foreach ($roles as $rol) {
            Roles::create($rol);
        }
    }
}
