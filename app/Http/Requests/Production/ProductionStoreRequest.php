<?php

namespace App\Http\Requests\Production;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductionStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'style_id' => 'required',
            'employee_id' => 'required',
            'cant' => 'required',
            'total' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'style_id.required' => 'El campo es obligatorio',
            'employee_id.required' => 'El campo es obligatorio',
            'cant.required' => 'El campo es obligatorio',
            'total.required' => 'El campo es obligatorio',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }
}
