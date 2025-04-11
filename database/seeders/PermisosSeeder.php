<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            ['nombre' => 'crear', 'descripcion' => 'Crear Encuestas'],
            ['nombre' => 'editar', 'descripcion' => 'Editar Encuestas'],
            ['nombre' => 'responder' , 'descripcion' => 'Responder Encuestas'],
            ['nombre' => 'ver', 'descripcion' => 'Acceso al panel de administración'],
        ];

        foreach ($permisos as $permiso) {
            Permiso::create($permiso);
        }
    }
}
