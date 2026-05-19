<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncuestasUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $encuestaUsuarioId = $this->route('encuestasUsuario') ?? $this->route('id');
        if ($encuestaUsuarioId) {
            $encuestaUsuario = \App\Models\EncuestasUsuario::find($encuestaUsuarioId);
            return $encuestaUsuario && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $encuestaUsuario);
        }
        return $this->user()->can('create', \App\Models\EncuestasUsuario::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_encuesta' => 'required',
			'id_usuario' => 'required',
            'ultima_pregunta_id' => 'nullable|integer',
            'completado' => 'nullable|boolean',
        ];
    }
}
