<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MensajesRespuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $respuestaId = $this->route('mensajesRespuesta') ?? $this->route('id');
        if ($respuestaId) {
            $respuesta = \App\Models\MensajesRespuesta::find($respuestaId);
            return $respuesta && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $respuesta);
        }
        return $this->user()->can('create', \App\Models\MensajesRespuesta::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_mensaje' => 'required',
			'id_usuario' => 'required',
			'respuesta' => 'required|string',
        ];
    }
}
