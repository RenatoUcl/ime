<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MensajeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $mensajeId = $this->route('mensaje') ?? $this->route('id');
        if ($mensajeId) {
            $mensaje = \App\Models\Mensaje::find($mensajeId);
            return $mensaje && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $mensaje);
        }
        return $this->user()->can('create', \App\Models\Mensaje::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_usuario_origen' => 'required',
			'id_usuario_destino' => 'required',
			'id_estado' => 'required',
			'asunto' => 'required|string',
			'mensaje' => 'required|string',
			'leido' => 'required',
			'readed_at' => 'required',
        ];
    }
}
