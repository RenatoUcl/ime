<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MensajesEstadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $estadoId = $this->route('mensajesEstado') ?? $this->route('id');
        if ($estadoId) {
            $estado = \App\Models\MensajesEstado::find($estadoId);
            return $estado && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $estado);
        }
        return $this->user()->can('create', \App\Models\MensajesEstado::class);
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
        ];
    }
}
