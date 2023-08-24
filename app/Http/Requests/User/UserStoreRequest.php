<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
        $rule = [
            'name' => 'required',
            'email' => [
                'required',
                'regex:"^[^@]+@[^@]+\.[a-zA-Z]{2,}$"',
                'unique:users,email,'.$this->id.',id,email,'.$this->email,
            ],
            'role_id' => 'required',
            'idNumber' => 'required',
            'identity_type_id' => 'required',
            'charge_id' => 'required',
        ];
        if (! $this->id) {
            $rule['password'] = 'required';
        }

        if ($this->rol_id!=2) {
            $rule['expeditionDate'] = "required";
            $rule['birthDate'] = "required";
            $rule['gender_id'] = "required";
            $rule['weight'] = "required";
            $rule['height'] = "required";
            $rule['civil_status_id'] = "required";
            $rule['phone'] = "required";
            $rule['cellphone'] = "required";
            $rule['address'] = "required";
            $rule['have_son'] = "required";
            $rule['nameContact'] = "required";
            $rule['relationshipContact'] = "required";
            $rule['phoneContact'] = "required";
            $rule['cellphoneContact'] = "required";
            $rule['emailContact'] = "required";
        }
        return $rule;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio',
            'email.required' => 'El campo es obligatorio',
            'email.regex' => 'El Correo debe contener un @ y una extensión',
            'email.unique' => 'El valor ya esta en uso',
            'password.required' => 'El campo es obligatorio',
            'role_id.required' => 'El campo es obligatorio',
            'idNumber.required' => 'El campo es obligatorio',
            'identity_type_id.required' => 'El campo es obligatorio',
            'charge_id.required' => 'El campo es obligatorio',

            //solo si el rol es afiliado
            "expeditionDate.required" => "El campo es obligatorio",
            "birthDate.required" => "El campo es obligatorio",
            "gender_id.required" => "El campo es obligatorio",
            "weight.required" => "El campo es obligatorio",
            "height.required" => "El campo es obligatorio",
            "civil_status_id.required" => "El campo es obligatorio",
            "phone.required" => "El campo es obligatorio",
            "cellphone.required" => "El campo es obligatorio",
            "address.required" => "El campo es obligatorio",
            "have_son.required" => "El campo es obligatorio",
            "nameContact.required" => "El campo es obligatorio",
            "relationshipContact.required" => "El campo es obligatorio",
            "phoneContact.required" => "El campo es obligatorio",
            "cellphoneContact.required" => "El campo es obligatorio",
            "emailContact.required" => "El campo es obligatorio",
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
