<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlternativaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $alternativaId = $this->route('alternativa') ?? $this->route('id');
        if ($alternativaId) {
            $alternativa = \App\Models\Alternativa::find($alternativaId);
            return $alternativa && $this->user()->can($this->isMethod('PUT') || $this->isMethod('PATCH') ? 'update' : ($this->isMethod('DELETE') ? 'delete' : 'view'), $alternativa);
        }
        return $this->user()->can('create', \App\Models\Alternativa::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'id_pregunta' => 'required',
            'id_dependencia' => 'required',
			'texto' => 'required|string',
			'valor' => 'required',
        ];
    }
}
