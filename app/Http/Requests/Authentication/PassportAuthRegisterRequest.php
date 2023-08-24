<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PassportAuthRegisterRequest extends FormRequest
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
            'lastName' => 'required',
            'identity_type_id' => 'required',
            'idNumber' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,12}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio',
            'lastName.required' => 'El campo es obligatorio',
            'identity_type_id.required' => 'El campo es obligatorio',
            'idNumber.required' => 'El campo es obligatorio',
            'email.required' => 'El campo es obligatorio',
            'email.unique' => 'El correo ya esta en uso',
            'email.email' => 'El campo de correo electrónico debe ser una dirección de correo electrónico válida.',
            'password.required' => 'El campo es obligatorio',
            'password.min' => 'El campo debe tener minimo 8 caracteres',
            'password.max' => 'El campo debe tener maximo 12 caracteres',
            'password.regex' => 'mínimo 1 mayúscula, 1 minúscula y 1 numero',
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
