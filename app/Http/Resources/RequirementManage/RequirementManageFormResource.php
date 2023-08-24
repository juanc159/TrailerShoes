<?php

namespace App\Http\Resources\RequirementManage;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequirementManageFormResource extends JsonResource
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
            'user' => [
                'id' => $this->user?->id,
                'full_name' => $this->user?->name.' '.$this->user?->lastName,
                'identity_type_name' => $this->user?->identityType?->name,
                'idNumber' => $this->user?->idNumber,
                'email' => $this->user?->email,
            ],
            'state' => $this->state,
            'type' => $this->type,
            'formData' => [
                'arrayInputs' => $this->type?->form?->inputs?->map(function ($value) use ($type) {
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
            'manages' => $this->manages->map(function ($value) {
                return [
                    'date' => Carbon::parse($value->created_at)->format('Y-m-d'),
                    'observation' => $value->observation,
                    'user_id' => $value->user_id,
                    'user_name' => $value->user?->name.' '.$value->user?->lastName,
                    'charge_name' => $value->user?->charge?->name,
                    'files' => $value->files->map(function ($file) {
                        return [
                            'name' => $file->name,
                            'path' => $file->path,
                        ];
                    }),
                ];
            }),
        ];
    }
}
