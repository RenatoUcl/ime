<?php

namespace App\Services;

use App\Models\MedioVerificacionArchivo;
use App\Models\MedioVerificacionAnalisis;
use App\Models\SubdimensionTermino;

use Smalot\PdfParser\Parser;

use PhpOffice\PhpWord\IOFactory;

class MedioVerificacionService
{
    public function analizar($archivo)
    {

        $texto = $this->extraerTexto(
            $archivo
        );

        $archivo->texto_extraido = $texto;
        $archivo->save();
        
        $subdimension =
            $archivo
            ->medioVerificacion
            ->id_subdimension;
        
        $terminos =
            SubdimensionTermino::with('termino')
            ->where(
                'id_subdimension',
                $subdimension
            )
            ->get();

        $encontrados = [];

        $faltantes = [];
        
        $puntaje = 0;
        
        $maximo = 0;
        
        foreach($terminos as $item)
        {
            $peso =
            $item->peso;

            $maximo += $peso;
            
            $buscar =
            mb_strtolower(
                $item->termino->termino
            );
            
            if(
                str_contains(
                    mb_strtolower($texto),
                    $buscar
                )
            ){
                $encontrados[]=[
                    'termino'=>$buscar,
                    'peso'=>$peso
                ];
                $puntaje += $peso;
            }
            else{
                if($item->obligatorio){
                    $faltantes[]=[
                        'termino'=>$buscar
                    ];
                }
            }

        }

        $porcentaje =
        
        $maximo > 0
        ? round(
            ($puntaje/$maximo)*100,
            2
          )
        :0;

        $resultado =
        
        $porcentaje >= 70
        ? 'cumple'
        : 'no_cumple';

        return MedioVerificacionAnalisis::create([
            'id_archivo'=>$archivo->id,
            'terminos_encontrados'=>$encontrados,
            'terminos_no_encontrados'=>$faltantes,
            'puntaje'=>$porcentaje,
            'resultado'=>$resultado,
            'observacion'=>
            "Análisis basado en términos configurados"
        ]);

    }

    private function extraerTexto($archivo)
    {
        $ruta =
        storage_path(
            'app/public/'.$archivo->archivo
        );

        $extension =
        strtolower(
            $archivo->extension
        );

        if($extension=='pdf')
        {
            $parser = new Parser();
            $pdf =
            $parser->parseFile($ruta);
            return $pdf->getText();
        }

        if(
            in_array(
                $extension,
                ['docx','doc']
            )
        )
        {
            $phpWord =
            IOFactory::load($ruta);
            $texto="";
            foreach(
                $phpWord->getSections()
                as $section
            ){
                foreach(
                    $section->getElements()
                    as $element
                ){
                    if(
                        method_exists(
                        $element,
                        'getText'
                        )
                    ){
                        $texto .=
                        $element->getText();
                    }
                }
            }
            return $texto;
        }
        return '';
    }

}
