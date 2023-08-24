<?php

namespace App\Http\Resources\RequirementType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementTypeListSelect2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = ['text', 'select', 'checkbox', 'radio', 'file'];

        return [
            'value' => $this->id,
            'title' => $this->name,
            'arrayInputs' => $this->form->inputs->map(function ($value) use ($type) {
                return [
                    'id' => $value->id,
                    'label' => $value->label,
                    'answer' => $value->type_input == 3 ? [] : '',
                    'type_input' => $value->type_input,
                    'type_name' => $type[$value->type_input - 1],
                    'required' => $value->required,
                    'arrayOptions' => $value->options->map(function ($value2) {
                        return [
                            'id' => $value2->id,
                            'name' => $value2->name,
                            'name' => $value2->name,
                        ];
                    }),
                ];
            }),
        ];
    }
}
