<?php

namespace App\Http\Resources\Requirement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementFormResource extends JsonResource
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
            'user_id' => $this->user_id,
            'state_id' => $this->requirement_state_id,
            'state' => $this->state?->name,
            'requirement_type_id' => $this->type?->id,
            'requirement_type_name' => $this->type?->name,
            'formData' => [
                'arrayInputs' => $this->type->form->inputs->map(function ($value) use ($type) {
                    $resp = $value->answer->where('user_id', $this->user_id)->where('requirement_id', $this->id)->first();
                    $answer = $resp->answer ?? null;
                    if ($value->type_input != 1 && $value->type_input != 5) {
                        $answer = intval($resp->answer);
                    }
                    if ($value->type_input == 3) {
                        $explode = explode(',', $resp->answer);
                        $answer = [];
                        foreach ($explode as $ex) {
                            $answer[] = intval($ex);
                        }
                    }

                    return [
                        'id' => $value->id,
                        'label' => $value->label,
                        'answer' => $answer ?? null,
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
            ],
        ];
    }
}
