<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LineasProgramaticas;
use App\Models\Dimension;
use App\Models\Subdimension;

class SubdimensionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Trayectoria' => [
                'Antecedentes',
                'Circuito de fallas y efectividad sistémicas',
                'Circuito de oportunidades según tipoligías de complejidad/calidad',
            ],
            'Modelos de intervención' => [
                'Enfoque y tipologías de complejidad',
                'Instrumentos',
                'Momentos de la intervención',
            ],
            'Autonomía de los equipos' => [
                'Seguridad',
                'Motivación',
                'Reconocimiento',
            ],
            'Confianza funcional' => [
                'Decisión funcional',
                'Anticipación',
                'Amplitud',
            ],
            'Condiciones básicas de operación' => [
                'Financiamiento',
                'Personal',
                'Infraestructura',
            ],
            'Coordinaciones territoriales' => [
                'Concentración de Externalidades',
                'Disponibilidad y calidad de servicios y equipamientos',
                'Articulación con la oferta local',
            ],
            'Coordinación funcional' => [
                'Intersectorialidad',
                'Coordinación interna y diferenciación funcional',
                'Descentralización administrativa',
            ],
            'Sistemas de regulación' => [
                'Sistema jurídico-normativo',
                'Sistema presupuestario',
                'Sistema de funcionamiento administrativo',
            ],
        ];

        $lineas = LineasProgramaticas::all();

        foreach ($lineas as $linea) {
            $pos=1;
            foreach ($data as $dimensionNombre => $subdimensiones) {
                $dimension = Dimension::where('nombre', $dimensionNombre)
                    ->where('id_linea', $linea->id)
                    ->first();
                
                if ($dimension) {
                    foreach ($subdimensiones as $sub) {
                        Subdimension::create([
                            'id_dimension' => $dimension->id,
                            'nombre' => $sub,
                            'descripcion' => "Subdimensión $sub para la dimensión $dimensionNombre en línea {$linea->nombre}",
                            'posicion' => $pos,
                        ]);
                    $pos++;
                    if ($pos==4) { $pos=1;}
                    }
                }
            }
        }
    }
}
