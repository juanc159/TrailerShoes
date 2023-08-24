<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventStoreRequest extends FormRequest
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
            'summary' => 'required',
            'start' => 'required',
            'end' => 'required',
            'calendar_type_id' => 'required',
            'link' => 'required',
            'location' => 'required',
            'description' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'summary.required' => 'El campo es obligatorio',
            'start.required' => 'El campo es obligatorio',
            'end.required' => 'El campo es obligatorio',
            'calendar_type_id.required' => 'El campo es obligatorio',
            'link.required' => 'El campo es obligatorio',
            'location.required' => 'El campo es obligatorio',
            'description.required' => 'El campo es obligatorio',
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
