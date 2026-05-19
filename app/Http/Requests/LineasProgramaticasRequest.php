<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineasProgramaticasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lineaId = $this->route('lineasProgramaticas') ?? $this->route('id');
        if ($lineaId) {
            $linea = \App\Models\LineasProgramaticas::find($lineaId);
            return $linea && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $linea);
        }
        return $this->user()->can('create', \App\Models\LineasProgramaticas::class);
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
