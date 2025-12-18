<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\LineasProgramaticasController;
use App\Http\Controllers\DimensionController;
use App\Http\Controllers\SubdimensionController;
use App\Http\Controllers\NivelesPrimarioController;
use App\Http\Controllers\NivelesSecundarioController;
use App\Http\Controllers\NivelesTerciarioController;
use App\Http\Controllers\CabeceraAlternativaController;
use App\Http\Controllers\CabeceraPreguntaController;
use App\Http\Controllers\CabeceraRespuestaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\AlternativaController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\IndiceMultiController;
use App\Http\Controllers\EncuestasAccesoController;
use App\Http\Controllers\EncuestaFlujoController;
use App\Http\Controllers\EncuestaInstanciaController;

use App\Models\Dimension;
use App\Models\Encuesta;

use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (){
    // Login
    Route::get('/',[AuthController::class, 'index'])->name('login');
    Route::post('/validar',[AuthController::class, 'validar'])->name('validar');
});

Route::middleware("auth")->group(function (){
    // Registro
    Route::get('/registro',[AuthController::class, 'registro'])
        ->name('registro');
    Route::post('/registrar',[AuthController::class, 'registrar'])->name('registrar');

    // Home & Dashboard
    Route::get('/home',[AuthController::class, 'home'])->name('home');
    
    // Logout
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    //Usuarios
    Route::controller(UserController::class)->group(function(){
        Route::get('/usuarios','index')->name('usuarios.index');
        Route::get('/usuarios/create','create')->name('usuarios.create');
        Route::post('/usuarios/store', 'store')->name('usuarios.store');
        Route::get('/usuarios/show/{id}', 'show')->name('usuarios.show');
        Route::get('/usuarios/edit/{id}', 'edit')->name('usuarios.edit');
        Route::put('/usuarios/update/{id}', 'update')->name('usuarios.update');
        Route::delete('/usuarios/destroy/{id}', 'destroy')->name('usuarios.destroy');
        Route::get('usuarios/{id}/roles', 'mostrarRoles')->name('usuarios.roles');
        Route::post('usuarios/{id}/roles', 'asignarRoles')->name('usuarios.roles.asignar');
    });

    // Roles
    Route::controller(RolesController::class)->group(function(){
        Route::get('/roles','index')->name('role.index');
        Route::get('/roles/create','create')->name('role.create');
        Route::post('/roles/store', 'store')->name('role.store');
        Route::get('/roles/show/{id}', 'show')->name('role.show');
        Route::get('/roles/edit/{id}', 'edit')->name('role.edit');
        Route::put('/roles/update/{id}', 'update')->name('role.update');
        Route::put('/roles/disabled/{id}', 'disabled')->name('role.disabled');
        Route::get('/roles/mostrar/{id}', 'mostrar')->name('role.mostrar');
        Route::put('/roles/asignar/{id}', 'asignar')->name('role.asignar');
        Route::get('roles/{id}/permisos', 'verPermisos')->name('roles.permisos');
        //Route::delete('/roles/destroy/{id}', 'destroy')->name('role.destroy');
    });

    // Permisos
    Route::controller(PermisoController::class)->group(function(){
        Route::get('/permisos','index')->name('permiso.index');
        Route::get('/permisos/create','create')->name('permiso.create');
        Route::post('/permisos/store', 'store')->name('permiso.store');
        Route::get('/permisos/show/{id}', 'show')->name('permiso.show');
        Route::get('/permisos/edit/{id}', 'edit')->name('permiso.edit');
        Route::put('/permisos/update/{permiso}', 'update')->name('permiso.update');
        Route::put('/permisos/disabled/{id}', 'disabled')->name('permiso.disabled');
        //Route::delete('/permisos/destroy/{id}', 'destroy')->name('permisos.destroy');
    });

    // Cargo
    Route::controller(CargoController::class)->group(function(){
        Route::get('/cargos','index')->name('cargo.index');
        Route::get('/cargos/create','create')->name('cargo.create');
        Route::post('/cargos/store', 'store')->name('cargo.store');
        Route::get('/cargos/show/{id}', 'show')->name('cargo.show');
        Route::get('/cargos/edit/{id}', 'edit')->name('cargo.edit');
        Route::put('/cargos/update/{id}', 'update')->name('cargo.update');
        Route::put('/cargos/disabled/{id}', 'disabled')->name('cargo.disabled');
        //Route::delete('/cargos/destroy/{id}', 'destroy')->name('cargo.destroy');
    });

    // Departamentos
    Route::controller(DepartamentoController::class)->group(function(){
        Route::get('/departamentos','index')->name('departamento.index');
        Route::get('/departamentos/create','create')->name('departamento.create');
        Route::post('/departamentos/store', 'store')->name('departamento.store');
        Route::get('/departamentos/show/{id}', 'show')->name('departamento.show');
        Route::get('/departamentos/edit/{id}', 'edit')->name('departamento.edit');
        Route::put('/departamentos/update/{id}', 'update')->name('departamento.update');
        Route::put('/departamentos/disabled/{id}', 'disabled')->name('departamento.disabled');
        //Route::delete('/departamentos/destroy/{id}', 'destroy')->name('departamento.destroy');
    });

    // Lineas Programaticas
    Route::controller(LineasProgramaticasController::class)->group(function(){
        Route::get('/lineas','index')->name('linea.index');
        Route::get('/lineas/create','create')->name('linea.create');
        Route::post('/lineas/store', 'store')->name('linea.store');
        Route::get('/lineas/show/{id}', 'show')->name('linea.show');
        Route::get('/lineas/edit/{id}', 'edit')->name('linea.edit');
        Route::put('/lineas/update/{id}', 'update')->name('linea.update');
        Route::put('/lineas/disabled/{id}', 'disabled')->name('linea.disabled');
        Route::delete('/lineas/destroy/{id}', 'destroy')->name('linea.destroy');
    });

    // Dimensiones
    Route::controller(DimensionController::class)->group(function(){
        Route::get('/dimension','index')->name('dimension.index');
        Route::get('/dimension/create','create')->name('dimension.create');
        Route::post('/dimension/store', 'store')->name('dimension.store');
        Route::get('/dimension/show/{id}', 'show')->name('dimension.show');
        Route::get('/dimension/edit/{id}', 'edit')->name('dimension.edit');
        Route::put('/dimension/update/{id}', 'update')->name('dimension.update');
        Route::put('/dimension/disabled/{id}', 'disabled')->name('dimension.disabled');
        //Route::delete('/dimension/destroy/{id}', 'destroy')->name('dimension.destroy');
    });

    // Subdimensiones
    Route::controller(SubdimensionController::class)->group(function(){
        Route::get('/subdimension','index')->name('subdimension.index');
        Route::get('/subdimension/create','create')->name('subdimension.create');
        Route::post('/subdimension/store', 'store')->name('subdimension.store');
        Route::get('/subdimension/show/{id}', 'show')->name('subdimension.show');
        Route::get('/subdimension/edit/{id}', 'edit')->name('subdimension.edit');
        Route::put('/subdimension/update/{subdimension}', 'update')->name('subdimension.update');
        Route::put('/subdimension/disabled/{id}', 'disabled')->name('subdimension.disabled');
        //Route::delete('/subdimension/destroy/{id}', 'destroy')->name('subdimension.destroy');
    });

    // Niveles Primarios
    Route::controller(NivelesPrimarioController::class)->group(function(){
        Route::get('/niveles-primario','index')->name('niveles-primario.index');
        Route::get('/niveles-primario/create','create')->name('niveles-primario.create');
        Route::post('/niveles-primario/store', 'store')->name('niveles-primario.store');
        Route::get('/niveles-primario/show/{id}', 'show')->name('niveles-primario.show');
        Route::get('/niveles-primario/edit/{id}', 'edit')->name('niveles-primario.edit');
        Route::put('/niveles-primario/update/{id}', 'update')->name('niveles-primario.update');
        Route::put('/niveles-primario/disabled/{id}', 'disabled')->name('niveles-primario.disabled');
        //Route::delete('/niveles-primario/destroy/{id}', 'destroy')->name('niveles-primario.destroy');
    });

    // Niveles Secundarios
    Route::controller(NivelesSecundarioController::class)->group(function(){
        Route::get('/niveles-secundario','index')->name('niveles-secundario.index');
        Route::get('/niveles-secundario/create','create')->name('niveles-secundario.create');
        Route::post('/niveles-secundario/store', 'store')->name('niveles-secundario.store');
        Route::get('/niveles-secundario/show/{id}', 'show')->name('niveles-secundario.show');
        Route::get('/niveles-secundario/edit/{id}', 'edit')->name('niveles-secundario.edit');
        Route::put('/niveles-secundario/update/{id}', 'update')->name('niveles-secundario.update');
        Route::put('/niveles-secundario/disabled/{id}', 'disabled')->name('niveles-secundario.disabled');
        //Route::delete('/niveles-secundario/destroy/{id}', 'destroy')->name('niveles-secundario.destroy');
    });

    // Niveles Terciarios
    Route::controller(NivelesTerciarioController::class)->group(function(){
        Route::get('/niveles-terciario','index')->name('niveles-terciario.index');
        Route::get('/niveles-terciario/create','create')->name('niveles-terciario.create');
        Route::post('/niveles-terciario/store', 'store')->name('niveles-terciario.store');
        Route::get('/niveles-terciario/show/{id}', 'show')->name('niveles-terciario.show');
        Route::get('/niveles-terciario/edit/{id}', 'edit')->name('niveles-terciario.edit');
        Route::put('/niveles-terciario/update/{id}', 'update')->name('niveles-terciario.update');
        Route::put('/niveles-terciario/disabled/{id}', 'disabled')->name('niveles-terciario.disabled');
        //Route::delete('/niveles-terciario/destroy/{id}', 'destroy')->name('niveles-terciario.destroy');
    });

    // Encuesta
    Route::controller(EncuestaController::class)->group(function(){
        Route::get('/encuesta','index')->name('encuesta.index');
        Route::get('/encuesta/create','create')->name('encuesta.create');
        Route::post('/encuesta/store', 'store')->name('encuesta.store');
        Route::get('/encuesta/show/{id}', 'show')->name('encuesta.show');
        Route::get('/encuesta/edit/{id}', 'edit')->name('encuesta.edit');
        Route::put('/encuesta/update/{id}', 'update')->name('encuesta.update');
        Route::put('/encuesta/disabled/{id}', 'disabled')->name('encuesta.disabled');
        Route::post('/encuesta/{id}/clonar','clonarEncuesta')->name('encuesta.clonar');
    });

    // Cabecera Pregunta
    Route::controller(CabeceraPreguntaController::class)->group(function(){
        Route::get('/cabecera-pregunta','index')->name('cabecera-pregunta.index');
        Route::get('/cabecera-pregunta/create','create')->name('cabecera-pregunta.create');
        Route::post('/cabecera-pregunta/store', 'store')->name('cabecera-pregunta.store');
        Route::get('/cabecera-pregunta/show/{id}', 'show')->name('cabecera-pregunta.show');
        Route::get('/cabecera-pregunta/edit/{id}', 'edit')->name('cabecera-pregunta.edit');
        Route::put('/cabecera-pregunta/update/{id}', 'update')->name('cabecera-pregunta.update');
        Route::put('/cabecera-pregunta/disabled/{id}', 'disabled')->name('cabecera-pregunta.disabled');
        //Route::delete('/cabecera-pregunta/destroy/{id}', 'destroy')->name('cabecera-pregunta.destroy');
    });

    // Cabecera Alternativa
    Route::controller(CabeceraAlternativaController::class)->group(function(){
        Route::get('/cabecera-alternativa','index')->name('cabecera-alternativa.index');
        Route::get('/cabecera-alternativa/create','create')->name('cabecera-alternativa.create');
        Route::post('/cabecera-alternativa/store', 'store')->name('cabecera-alternativa.store');
        Route::get('/cabecera-alternativa/show/{id}', 'show')->name('cabecera-alternativa.show');
        Route::get('/cabecera-alternativa/edit/{id}', 'edit')->name('cabecera-alternativa.edit');
        Route::put('/cabecera-alternativa/update/{id}', 'update')->name('cabecera-alternativa.update');
        Route::put('/cabecera-alternativa/disabled/{id}', 'disabled')->name('cabecera-alternativa.disabled');
        //Route::delete('/cabecera-alternativa/destroy/{id}', 'destroy')->name('cabecera-alternativa.destroy');
    });

    // Cabecera Respuesta
    Route::controller(CabeceraRespuestaController::class)->group(function(){
        Route::get('/cabecera-respuesta','index')->name('cabecera-respuesta.index');
        Route::get('/cabecera-respuesta/create','create')->name('cabecera-respuesta.create');
        Route::post('/cabecera-respuesta/store', 'store')->name('cabecera-respuesta.store');
        Route::get('/cabecera-respuesta/show/{id}', 'show')->name('cabecera-respuesta.show');
        Route::get('/cabecera-respuesta/edit/{id}', 'edit')->name('cabecera-respuesta.edit');
        Route::put('/cabecera-respuesta/update/{id}', 'update')->name('cabecera-respuesta.update');
        Route::put('/cabecera-respuesta/disabled/{id}', 'disabled')->name('cabecera-respuesta.disabled');
        //Route::delete('/cabecera-respuesta/destroy/{id}', 'destroy')->name('cabecera-respuesta.destroy');
    });

    // Encuesta Pregunta
    Route::controller(PreguntaController::class)->group(function(){
        Route::get('/pregunta','index')->name('pregunta.index');
        Route::get('/pregunta/create','create')->name('pregunta.create');
        Route::post('/pregunta/store', 'store')->name('pregunta.store');
        Route::get('/pregunta/show/{id}', 'show')->name('pregunta.show');
        Route::get('/pregunta/edit/{id}', 'edit')->name('pregunta.edit');
        Route::put('/pregunta/update/{id}', 'update')->name('pregunta.update');
        Route::get('/pregunta/disabled/{id}', 'disabled')->name('pregunta.disabled');
        //Route::delete('/pregunta/destroy/{id}', 'destroy')->name('pregunta.destroy');
    });

    // Encuesta Alternativa
    Route::controller(AlternativaController::class)->group(function(){
        Route::get('/alternativa','index')->name('alternativa.index');
        Route::get('/alternativa/create','create')->name('alternativa.create');
        Route::post('/alternativa/store', 'store')->name('alternativa.store');
        Route::get('/alternativa/show/{id}', 'show')->name('alternativa.show');
        Route::get('/alternativa/edit/{id}', 'edit')->name('alternativa.edit');
        Route::put('/alternativa/update/{alternativa}', 'update')->name('alternativa.update');
        Route::get('/alternativa/disabled/{id}', 'disabled')->name('alternativa.disabled');
        //Route::delete('/alternativa/destroy/{id}', 'destroy')->name('alternativa.destroy');
    });

    // Encuesta Respuesta
    Route::controller(RespuestaController::class)->group(function(){
        Route::get('/respuesta','index')->name('respuesta.index');
        Route::get('/respuesta/create','create')->name('respuesta.create');
        Route::post('/respuesta/store', 'store')->name('respuesta.store');
        Route::get('/respuesta/show/{id}', 'show')->name('respuesta.show');
        Route::get('/respuesta/edit/{id}', 'edit')->name('respuesta.edit');
        Route::put('/respuesta/update/{id}', 'update')->name('respuesta.update');
        Route::put('/respuesta/disabled/{id}', 'disabled')->name('respuesta.disabled');
    });

    Route::controller(ResponderController::class)->group(function(){
        Route::get('/responder','index')->name('responder.index');
        Route::get('/responder/{idEncuesta}', 'mostrar')->name('responder.mostrar');
        Route::post('/responder/guardar', 'guardar')->name('responder.guardar');
        Route::get('/responder/{id}/continuar','continuar')->name('responder.continuar');
        Route::post('/responder/guardar-respuestas-grupo','guardarRespuestasGrupo')->name('responder.guardarRespuestasGrupo');
        Route::get('/responder/{id_encuesta}/grupo/{grupo}', 'mostrarGrupo')->name('responder.mostrarGrupo');
    });

    Route::controller(IndiceMultiController::class)
        ->group(function(){
            //Route::get('/indice','index')->name('indice.index');
            //Route::get('/indice/{idEncuesta}/{idDimension}', 'detalle')->name('indice.detalle');
            Route::get('/indice', 'index')->name('indice.index');
            Route::post('/indice/resultados', 'resultados')->name('indice.resultados');
            Route::post('/indice/comparar', 'comparar')->name('indice.comparar');
            Route::get('/indice/detalle/{encuesta}/{instancia}/{dimension}', 'detalle')->name('indice.detalle');
            Route::get('/indice/dimension/{encuesta}/{dimension}/{instancia}','verDimension')->name('indice.dimension.ver');
            Route::get('/indice/semaforo/{encuesta}/{instancia}','semaforo')->name('indice.semaforo');
    });

    Route::controller(EncuestasAccesoController::class)->group(function(){
        Route::get('/encuestas/accesos', 'index')->name('encuestas.accesos.index');
        Route::get('/encuestas/accesos/create', 'create')->name('encuestas.accesos.create');
        Route::post('/encuestas/accesos/store', 'store')->name('encuestas.accesos.store');
        Route::get('/encuestas/accesos/{id}/edit', 'edit')->name('encuestas.accesos.edit');
        Route::put('/encuestas/accesos/{id}/update', 'update')->name('encuestas.accesos.update');
        Route::delete('/encuestas/accesos/{id}', 'destroy')->name('encuestas.accesos.destroy');
    });

    Route::get('/encuestas/{id}/dimensiones', function ($id) {
        $encuesta = Encuesta::findOrFail($id);
        // Buscamos dimensiones por la línea de la encuesta
        $dimensiones = Dimension::where('id_linea', $encuesta->id_linea)
                                ->orderBy('posicion', 'asc')
                                ->get();
        return response()->json($dimensiones);
    });

    Route::controller(EncuestaFlujoController::class)->group(function(){
        Route::get('/encuestas/{encuestaId}/flujo/grupo/{grupo}', 'showGrupo')->name('encuestas.flujo.grupo');
        Route::get('/encuestas/{encuestaId}/flujo', 'start')->name('encuestas.flujo.start');
        Route::post('/encuestas/{encuestaId}/fluir/guardar', 'guardarRespuestaAjax')->name('encuestas.flujo.guardar');
        Route::get('/encuestas/{encuestaId}/fluir/finalizar}','finalizar')->name('encuestas.flujo.finalizar');
    });

    Route::get('/encuestas/accesos-matriz', [EncuestasAccesoController::class, 'matriz'])->name('encuestas.accesos.matriz');

    // Instancias Encuestas
    Route::controller(EncuestaInstanciaController::class)
        ->prefix('encuestas/{encuesta}')
        ->group(function () {
            Route::get('periodos','index')->name('encuestas.periodos.index');
            Route::get('periodos/crear', 'create')->name('encuestas.periodos.create');
            Route::post('periodos', 'store')->name('encuestas.periodos.store');
    });
    Route::get('periodos/{id}/editar',[EncuestaInstanciaController::class, 'edit'])->name('encuestas.periodos.edit');
    Route::put('periodos/{id}',[EncuestaInstanciaController::class, 'update'])->name('encuestas.periodos.update');
    Route::delete('periodos/{id}',[EncuestaInstanciaController::class, 'destroy'])->name('encuestas.periodos.destroy');

});                                                                                                                                         