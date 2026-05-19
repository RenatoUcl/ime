<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Encuesta;
use App\Models\Respuesta;
use App\Models\Roles;
use App\Models\Alternativa;
use App\Models\Pregunta;
use App\Models\Permiso;
use App\Models\Dimension;
use App\Models\Subdimension;
use App\Models\Cargos;
use App\Models\Departamento;
use App\Models\LineasProgramaticas;
use App\Models\Archivo;
use App\Models\EncuestasArchivo;
use App\Models\EncuestasUsuario;
use App\Models\Configuracion;
use App\Models\Mensaje;
use App\Models\MensajesArchivo;
use App\Models\MensajesEstado;
use App\Models\MensajesRespuesta;
use App\Models\NivelesPrimario;
use App\Models\NivelesSecundario;
use App\Models\NivelesTerciario;
use App\Models\CabeceraAlternativa;
use App\Models\CabeceraPregunta;
use App\Models\CabeceraRespuesta;

use App\Policies\UserPolicy;
use App\Policies\EncuestaPolicy;
use App\Policies\RespuestaPolicy;
use App\Policies\RolesPolicy;
use App\Policies\AlternativaPolicy;
use App\Policies\PreguntaPolicy;
use App\Policies\PermisoPolicy;
use App\Policies\DimensionPolicy;
use App\Policies\SubdimensionPolicy;
use App\Policies\CargoPolicy;
use App\Policies\DepartamentoPolicy;
use App\Policies\LineasProgramaticasPolicy;
use App\Policies\ArchivoPolicy;
use App\Policies\EncuestasArchivoPolicy;
use App\Policies\EncuestasUsuarioPolicy;
use App\Policies\ConfiguracionPolicy;
use App\Policies\MensajePolicy;
use App\Policies\MensajesArchivoPolicy;
use App\Policies\MensajesEstadoPolicy;
use App\Policies\MensajesRespuestaPolicy;
use App\Policies\NivelesPrimarioPolicy;
use App\Policies\NivelesSecundarioPolicy;
use App\Policies\NivelesTerciarioPolicy;
use App\Policies\CabeceraAlternativaPolicy;
use App\Policies\CabeceraPreguntaPolicy;
use App\Policies\CabeceraRespuestaPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Encuesta::class, EncuestaPolicy::class);
        Gate::policy(Respuesta::class, RespuestaPolicy::class);
        Gate::policy(Roles::class, RolesPolicy::class);
        Gate::policy(Alternativa::class, AlternativaPolicy::class);
        Gate::policy(Pregunta::class, PreguntaPolicy::class);
        Gate::policy(Permiso::class, PermisoPolicy::class);
        Gate::policy(Dimension::class, DimensionPolicy::class);
        Gate::policy(Subdimension::class, SubdimensionPolicy::class);
        Gate::policy(Cargos::class, CargoPolicy::class);
        Gate::policy(Departamento::class, DepartamentoPolicy::class);
        Gate::policy(LineasProgramaticas::class, LineasProgramaticasPolicy::class);
        Gate::policy(Archivo::class, ArchivoPolicy::class);
        Gate::policy(EncuestasArchivo::class, EncuestasArchivoPolicy::class);
        Gate::policy(EncuestasUsuario::class, EncuestasUsuarioPolicy::class);
        Gate::policy(Configuracion::class, ConfiguracionPolicy::class);
        Gate::policy(Mensaje::class, MensajePolicy::class);
        Gate::policy(MensajesArchivo::class, MensajesArchivoPolicy::class);
        Gate::policy(MensajesEstado::class, MensajesEstadoPolicy::class);
        Gate::policy(MensajesRespuesta::class, MensajesRespuestaPolicy::class);
        Gate::policy(NivelesPrimario::class, NivelesPrimarioPolicy::class);
        Gate::policy(NivelesSecundario::class, NivelesSecundarioPolicy::class);
        Gate::policy(NivelesTerciario::class, NivelesTerciarioPolicy::class);
        Gate::policy(CabeceraAlternativa::class, CabeceraAlternativaPolicy::class);
        Gate::policy(CabeceraPregunta::class, CabeceraPreguntaPolicy::class);
        Gate::policy(CabeceraRespuesta::class, CabeceraRespuestaPolicy::class);
    }
}
