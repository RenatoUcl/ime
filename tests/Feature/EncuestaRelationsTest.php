<?php

namespace Tests\Feature;

use App\Models\CabeceraPregunta;
use App\Models\Encuesta;
use App\Models\EncuestasArchivo;
use App\Models\EncuestasUsuario;
use App\Models\LineasProgramaticas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncuestaRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_relations_return_only_linked_records(): void
    {
        $linea = LineasProgramaticas::create([
            'nombre' => 'Línea 1',
            'descripcion' => 'Descripción de la línea',
            'estado' => true,
        ]);

        $encuesta = Encuesta::create([
            'id_linea' => $linea->id,
            'nombre' => 'Encuesta principal',
            'descripcion' => 'Descripción de la encuesta',
            'estado' => true,
        ]);

        $otraEncuesta = Encuesta::create([
            'id_linea' => $linea->id,
            'nombre' => 'Otra encuesta',
            'descripcion' => 'Descripción secundaria',
            'estado' => true,
        ]);

        $cabeceraEsperada = CabeceraPregunta::create([
            'id_encuesta' => $encuesta->id,
            'tipo' => true,
            'pregunta' => 'Pregunta principal',
            'estado' => true,
        ]);
        CabeceraPregunta::create([
            'id_encuesta' => $otraEncuesta->id,
            'tipo' => false,
            'pregunta' => 'Pregunta secundaria',
            'estado' => true,
        ]);

        $archivoEsperado = EncuestasArchivo::create([
            'id_encuesta' => $encuesta->id,
            'nombre' => 'Archivo 1',
            'archivo' => 'ruta/archivo1.pdf',
        ]);
        EncuestasArchivo::create([
            'id_encuesta' => $otraEncuesta->id,
            'nombre' => 'Archivo 2',
            'archivo' => 'ruta/archivo2.pdf',
        ]);

        $usuario = User::create([
            'nombre' => 'Usuario',
            'ap_paterno' => 'Principal',
            'ap_materno' => 'Prueba',
            'email' => 'principal@example.com',
            'password' => 'password',
            'telefono' => '123456789',
        ]);
        $otroUsuario = User::create([
            'nombre' => 'Usuario',
            'ap_paterno' => 'Secundario',
            'ap_materno' => 'Prueba',
            'email' => 'secundario@example.com',
            'password' => 'password',
            'telefono' => '123456789',
        ]);

        $usuarioEsperado = EncuestasUsuario::create([
            'id_encuesta' => $encuesta->id,
            'id_usuario' => $usuario->id,
        ]);
        EncuestasUsuario::create([
            'id_encuesta' => $otraEncuesta->id,
            'id_usuario' => $otroUsuario->id,
        ]);

        $encuesta->load(['cabeceraPreguntas', 'encuestasArchivos', 'encuestasUsuarios']);

        $this->assertTrue($encuesta->cabeceraPreguntas->contains($cabeceraEsperada));
        $this->assertSame([$encuesta->id], $encuesta->cabeceraPreguntas->pluck('id_encuesta')->unique()->all());

        $this->assertTrue($encuesta->encuestasArchivos->contains($archivoEsperado));
        $this->assertSame([$encuesta->id], $encuesta->encuestasArchivos->pluck('id_encuesta')->unique()->all());

        $this->assertTrue($encuesta->encuestasUsuarios->contains($usuarioEsperado));
        $this->assertSame([$encuesta->id], $encuesta->encuestasUsuarios->pluck('id_encuesta')->unique()->all());
    }
}
