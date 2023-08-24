<?php

namespace App\Http\Resources\RequirementType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementTypeFormResource extends JsonResource
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
            'id' => $this->id,
            'form_id' => $this->form?->id,
            'name' => $this->name,
            'internal' => $this->internal,
            'external' => $this->external,
            'arrayCharges' => $this->charges->map(function ($value) {
                return [
                    'charge_id' => $value->id,
                    'name' => $value->name,
                    'order' => $value->pivot->order,
                ];
            }),
            'arrayInputs' => $this->form->inputs->map(function ($value) use ($type) {
                return [
                    'id' => $value->id,
                    'label' => $value->label,
                    'type_input' => $value->type_input,
                    'type_name' => $type[$value->type_input - 1],
                    'required' => $value->required,
                    'arrayOptions' => $value->options->map(function ($value2) {
                        return [
                            'id' => $value2->id,
                            'name' => $value2->name,
                        ];
                    }),
                ];
            }),
        ];
    }
}
