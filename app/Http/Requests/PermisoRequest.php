<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permisoId = $this->route('permiso') ?? $this->route('id');
        if ($permisoId) {
            $permiso = \App\Models\Permiso::find($permisoId);
            return $permiso && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $permiso);
        }
        return $this->user()->can('create', \App\Models\Permiso::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
			'estado' => 'required',
        ];
    }
}
