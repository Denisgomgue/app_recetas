<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RecetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ingredientes' => 'required|array|min:1',
            'ingredientes.*' => 'required|string|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ingredientes.required' => 'Debes proporcionar al menos un ingrediente',
            'ingredientes.array' => 'Los ingredientes deben enviarse como un array',
            'ingredientes.min' => 'Debes proporcionar al menos un ingrediente',
            'ingredientes.*.required' => 'El ingrediente no puede estar vacío',
            'ingredientes.*.string' => 'El ingrediente debe ser texto',
            'ingredientes.*.max' => 'El ingrediente no puede exceder los 100 caracteres',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }
}
