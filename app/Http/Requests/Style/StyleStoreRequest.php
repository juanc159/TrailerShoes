<?php

namespace App\Http\Requests\Style;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StyleStoreRequest extends FormRequest
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
            'name' => 'required',
            'charge_id' => 'required',
            'price' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio',
            'charge_id.required' => 'El campo es obligatorio',
            'price.required' => 'El campo es obligatorio',
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
