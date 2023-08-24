<?php

namespace App\Http\Resources\Form;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = ['text', 'select', 'checkbox', 'radio'];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'arrayInputs' => $this->inputs->map(function ($value) use ($type) {
                return [
                    'id' => $value->id,
                    'label' => $value->label,
                    'type_input' => $value->type_input,
                    'type_name' => $type[$value->type_input - 1],
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
