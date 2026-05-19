<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DimensionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $dimensionId = $this->route('dimension') ?? $this->route('id');
        if ($dimensionId) {
            $dimension = \App\Models\Dimension::find($dimensionId);
            return $dimension && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $dimension);
        }
        return $this->user()->can('create', \App\Models\Dimension::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_linea' => 'required',
			'nombre' => 'required|string',
			'descripcion' => 'required|string',
            'posicion' => 'required|integer',
			'estado' => 'required',
        ];
    }
}
