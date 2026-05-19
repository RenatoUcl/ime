<?php

namespace App\Traits;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::registrarAuditoria('created', $model, null, $model->getAttributes());
        });

        static::updating(function ($model) {
            self::registrarAuditoria('updated', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            if ($model->isForceDeleting()) {
                self::registrarAuditoria('force_deleted', $model, $model->getAttributes(), null);
            } else {
                self::registrarAuditoria('deleted', $model, $model->getAttributes(), null);
            }
        });

        static::restored(function ($model) {
            self::registrarAuditoria('restored', $model, null, $model->getAttributes());
        });
    }

    protected static function registrarAuditoria(string $accion, $model, ?array $datosAnteriores, ?array $datosNuevos): void
    {
        $excluded = ['created_at', 'updated_at', 'deleted_at', 'remember_token', 'password'];

        $limpiar = function ($data) use ($excluded) {
            if (!$data) return null;
            return array_diff_key($data, array_flip($excluded));
        };

        $anteriores = $limpiar($datosAnteriores);
        $nuevos = $limpiar($datosNuevos);

        if (empty($anteriores) && empty($nuevos)) {
            return;
        }

        Auditoria::create([
            'id_usuario'       => Auth::id(),
            'accion'           => $accion,
            'modelo'           => get_class($model),
            'modelo_id'        => $model->getKey(),
            'datos_anteriores' => $anteriores,
            'datos_nuevos'     => $nuevos,
            'ip_address'       => request()->ip(),
            'user_agent'       => request()->userAgent(),
        ]);
    }
}
